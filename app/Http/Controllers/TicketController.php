<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\Bast;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedBranch = $request->input('branch');
        $selectedStatus = $request->input('status');

        $query = Ticket::with(['asset', 'user', 'bast'])->latest();

        if ($user->role === 'OUTLET') {
            $userBranch = trim($user->branch_code ?? '');
            $query->where(function ($q) use ($user, $userBranch) {
                $q->where('user_id', $user->id);
                if (!empty($userBranch)) {
                    $q->orWhere(function ($sub) use ($userBranch) {
                        $sub->whereNotNull('branch_code')
                            ->where('branch_code', '!=', '')
                            ->where('branch_code', $userBranch);
                    });
                }
            });
        } elseif ($user->role === 'IT') {
            // Role IT dapat melihat tiket departemen IT DAN MAINTENANCE
            $query->whereIn('department', ['IT', 'MAINTENANCE']);
        } elseif ($user->role === 'MAINTENANCE') {
            // Role MAINTENANCE hanya dapat melihat tiket departemen MAINTENANCE saja
            $query->where('department', 'MAINTENANCE');
        }
        // Jika ADMIN, tidak perlu pembatasan departemen (bisa melihat semua)

        if (!empty($selectedBranch)) {
            $query->where(function ($q) use ($selectedBranch) {
                $q->where('branch_code', $selectedBranch)
                  ->orWhere('branch_name', $selectedBranch)
                  ->orWhereHas('user', function ($subQuery) use ($selectedBranch) {
                      $subQuery->where('branch_code', $selectedBranch)
                               ->orWhere('branch_name', $selectedBranch);
                  });
            });
        }

        if (!empty($selectedStatus)) {
            $query->where(function ($q) use ($selectedStatus) {
                if ($selectedStatus === 'Menunggu Konfirmasi') {
                    $q->where('status', 'Menunggu Konfirmasi');
                } elseif ($selectedStatus === 'Completed') {
                    $q->where(function ($sub) {
                        $sub->whereIn('status', ['Selesai', 'Completed'])
                            ->orWhere('work_status', 'Completed');
                    })->where('status', '!=', 'Menunggu Konfirmasi');
                } else {
                    $q->where('status', $selectedStatus)
                      ->orWhere('work_status', $selectedStatus);
                }
            });
        }

        $tickets = $query->get();

        $branchCodes = User::whereNotNull('branch_code')->pluck('branch_code');
        $assetBranches = Asset::whereNotNull('branch_code')->pluck('branch_code');

        $branches = $branchCodes
            ->merge($assetBranches)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('tickets.index', compact('tickets', 'branches'));
    }

    public function create()
    {
        $assets = Asset::orderBy('asset_name', 'asc')->get();

        $branches = Asset::whereNotNull('branch_code')
                         ->where('branch_code', '!=', '')
                         ->select('branch_code', 'branch_name')
                         ->distinct()
                         ->pluck('branch_name', 'branch_code')
                         ->toArray();

        if (empty($branches)) {
            $branches = [
                'HOTNG' => 'HEAD OFFICE TANGERANG',
            ];
        }

        return view('tickets.create', compact('assets', 'branches'));
    }

    public function store(Request $request, TelegramService $telegram)
    {
        $request->validate([
            'reporter_name'     => 'required|string|max:255',
            'asset_id'          => 'nullable|exists:assets,id',
            'custom_asset_name' => 'nullable|string|max:255',
            'department'        => 'required|in:IT,MAINTENANCE',
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'priority'          => 'required|in:Rendah,Sedang,Tinggi',
            'branch_code'       => 'nullable|string',
            'attachment'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if (empty($request->asset_id) && empty($request->custom_asset_name)) {
            return back()->withErrors(['asset_id' => 'Pilih aset dari daftar atau ketik nama aset secara manual.'])->withInput();
        }

        $asset = $request->asset_id ? Asset::find($request->asset_id) : null;
        $user  = auth()->user();

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            // Memastikan file tersimpan ke disk eksternal
            $attachmentPath = $request->file('attachment')->store('tickets/attachments', 'external_uploads');
        }

        $branchCode = $request->filled('branch_code') 
            ? $request->branch_code 
            : ($user->branch_code ?? ($asset->branch_code ?? 'HOTNG'));

        $branchName = User::where('branch_code', $branchCode)->value('branch_name')
            ?? Asset::where('branch_code', $branchCode)->value('branch_name')
            ?? $user->branch_name 
            ?? ($asset->branch_name ?? 'HEAD OFFICE TANGERANG');

        $ticketNumber = 'TKT-' . date('Ymd') . '-' . rand(1000, 9999);

        $ticket = Ticket::create([
            'ticket_number'     => $ticketNumber,
            'user_id'           => $user->id,
            'reporter_name'     => $request->reporter_name,
            'asset_id'          => $request->asset_id,
            'custom_asset_name' => $request->custom_asset_name,
            'department'        => $request->department,
            'title'             => $request->title,
            'description'       => $request->description,
            'attachment'        => $attachmentPath,
            'priority'          => $request->priority,
            'branch_code'       => $branchCode,
            'branch_name'       => $branchName,
            'status'            => 'Terbuka',
        ]);

        try {
            $assetDisplayName = $asset->asset_name ?? ($request->custom_asset_name . ' (Manual)');
            $cleanTitle       = htmlspecialchars($request->title, ENT_QUOTES, 'UTF-8');
            $cleanDesc        = htmlspecialchars($request->description, ENT_QUOTES, 'UTF-8');
            $cleanReporter    = htmlspecialchars($request->reporter_name, ENT_QUOTES, 'UTF-8');
            $division         = $request->department;

            $message  = "🚨 <b>TIKET PERBAIKAN BARU</b>\n\n";
            $message .= "<b>No. Tiket:</b> #" . $ticketNumber . "\n";
            $message .= "<b>Cabang:</b> " . $branchName . "\n";
            $message .= "<b>Pelapor:</b> " . $cleanReporter . "\n";
            $message .= "<b>Divisi Tujuan:</b> " . $division . "\n";
            $message .= "<b>Aset:</b> " . $assetDisplayName . "\n";
            $message .= "<b>Prioritas:</b> " . $request->priority . "\n";
            $message .= "<b>Judul:</b> " . $cleanTitle . "\n";
            $message .= "<b>Kendala:</b> " . $cleanDesc . "\n\n";
            $message .= "<i>Silakan login ke portal untuk memproses tiket ini.</i>";

            if ($attachmentPath && in_array(pathinfo($attachmentPath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $fullPhotoPath = 'C:\Users\USER\Documents\IT\ngrok-v3-stable-windows-386\lampiran-portal\\' . $attachmentPath;
                if (file_exists($fullPhotoPath)) {
                    $telegram->sendPhoto($fullPhotoPath, $message, $division);
                } else {
                    $telegram->sendMessage($message, $division);
                }
            } else {
                $telegram->sendMessage($message, $division);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi Telegram tiket baru: ' . $e->getMessage());
        }

        return redirect()->route('tickets.index')->with('success', 'Tiket perbaikan berhasil dikirim.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['asset', 'user', 'bast.technician'])->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['ADMIN', 'IT']) && !$user->hasRole('ADMIN')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menghapus tiket ini.');
        }

        $ticket = Ticket::with(['bast', 'asset'])->findOrFail($id);

        if ($ticket->attachment && Storage::disk('external_uploads')->exists($ticket->attachment)) {
            Storage::disk('external_uploads')->delete($ticket->attachment);
        }

        if ($ticket->bast && $ticket->bast->attachment && Storage::disk('external_uploads')->exists($ticket->bast->attachment)) {
            Storage::disk('external_uploads')->delete($ticket->bast->attachment);
        }

        if ($ticket->bast) {
            $ticket->bast->delete();
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Tiket perbaikan berhasil dihapus.');
    }

    public function updateWork(Request $request, $id)
    {
        $request->validate([
            'technician_name'        => 'required|string|max:255',
            'action_taken'           => 'required|string',
            'work_status'            => 'required|in:On Check,Pengajuan Sparepart,Completed',
            'target_completion_date' => 'required_unless:work_status,Completed|nullable|date',
        ]);

        $ticket = Ticket::findOrFail($id);
        $newMainStatus = ($request->work_status === 'Completed') ? $ticket->status : 'Diproses';

        $ticket->update([
            'technician_name'        => $request->technician_name,
            'action_taken'           => $request->action_taken,
            'target_completion_date' => $request->work_status === 'Completed' ? null : $request->target_completion_date,
            'work_status'            => $request->work_status,
            'status'                 => $newMainStatus,
        ]);

        return redirect()->back()->with('success', 'Progress pengerjaan (WORK) berhasil diperbarui.');
    }

    public function createBast($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        if ($ticket->bast) {
            return redirect()->route('tickets.show', $id)->with('info', 'BAST untuk tiket ini sudah diisi.');
        }

        return view('tickets.bast.create', compact('ticket'));
    }

    public function storeBast(Request $request, $id, TelegramService $telegram)
    {
        $request->validate([
            'action_taken'   => 'required|string',
            'parts_replaced' => 'nullable|string',
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $ticket = Ticket::findOrFail($id);

        $attachmentPath = optional($ticket->bast)->attachment ?? null;
        if ($request->hasFile('attachment')) {
            if ($attachmentPath && Storage::disk('external_uploads')->exists($attachmentPath)) {
                Storage::disk('external_uploads')->delete($attachmentPath);
            }
            // Menyimpan file BAST ke disk eksternal
            $attachmentPath = $request->file('attachment')->store('basts', 'external_uploads');
        }

        $ticket->bast()->updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'technician_id'   => auth()->id(),
                'technician_name' => $request->technician_name ?? auth()->user()->name,
                'action_taken'    => $request->action_taken,
                'parts_replaced'  => $request->parts_replaced,
                'attachment'      => $attachmentPath ?? optional($ticket->bast)->attachment ?? null,
            ]
        );

        $ticket->update(['status' => 'Menunggu Konfirmasi']);

        try {
            $techName    = $request->technician_name ?? auth()->user()->name;
            $cleanAction = htmlspecialchars($request->action_taken, ENT_QUOTES, 'UTF-8');
            $cleanParts  = htmlspecialchars($request->parts_replaced ?? '-', ENT_QUOTES, 'UTF-8');
            $division    = $ticket->department;

            $message  = "🛠️ <b>BERITA ACARA SERAH TERIMA (BAST) DIBUAT</b>\n\n";
            $message .= "<b>No. Tiket:</b> #" . $ticket->ticket_number . "\n";
            $message .= "<b>Cabang:</b> " . $ticket->branch_name . "\n";
            $message .= "<b>Teknisi:</b> " . $techName . "\n";
            $message .= "<b>Divisi:</b> " . $division . "\n";
            $message .= "<b>Tindakan Perbaikan:</b> " . $cleanAction . "\n";
            $message .= "<b>Sparepart Diganti:</b> " . $cleanParts . "\n";
            $message .= "<b>Status:</b> Menunggu Konfirmasi DONE dari Outlet\n\n";
            $message .= "<i>Silakan login ke portal untuk melakukan konfirmasi penyelesaian.</i>";

            if ($attachmentPath && in_array(pathinfo($attachmentPath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $fullPhotoPath = 'C:\Users\USER\Documents\IT\ngrok-v3-stable-windows-386\lampiran-portal\\' . $attachmentPath;
                if (file_exists($fullPhotoPath)) {
                    $telegram->sendPhoto($fullPhotoPath, $message, $division);
                } else {
                    $telegram->sendMessage($message, $division);
                }
            } else {
                $telegram->sendMessage($message, $division);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi Telegram BAST: ' . $e->getMessage());
        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Form BAST berhasil dibuat. Menunggu konfirmasi DONE dari user.');
    }

    public function markAsDone(Request $request, $id, TelegramService $telegram)
    {
        $ticket = Ticket::findOrFail($id);

        if (in_array(auth()->user()->role, ['IT', 'MAINTENANCE'])) {
            return redirect()->back()->with('error', 'Hanya Pelapor / Outlet yang berhak mengonfirmasi tiket Selesai (DONE).');
        }

        if ($ticket->user_id !== auth()->id() && auth()->user()->role !== 'ADMIN') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah status tiket ini.');
        }

        $request->validate([
            'completed_at' => 'nullable|date',
        ]);

        $completedAt = $request->filled('completed_at') 
            ? Carbon::parse($request->completed_at) 
            : now();

        $ticket->update([
            'status'       => 'Selesai',
            'completed_at' => $completedAt,
        ]);

        if ($ticket->bast) {
            $ticket->bast->update([
                'completed_at' => $completedAt,
            ]);
        }

        try {
            $division = $ticket->department;

            $message  = "✅ <b>TIKET PERBAIKAN SELESAI (DONE)</b>\n\n";
            $message .= "<b>No. Tiket:</b> #" . $ticket->ticket_number . "\n";
            $message .= "<b>Cabang:</b> " . $ticket->branch_name . "\n";
            $message .= "<b>Divisi:</b> " . $division . "\n";
            $message .= "<b>Pelapor:</b> " . htmlspecialchars($ticket->reporter_name, ENT_QUOTES, 'UTF-8') . "\n";
            $message .= "<b>Status:</b> Selesai\n";
            $message .= "<b>Tanggal Selesai:</b> " . $completedAt->format('d/m/Y H:i') . "\n";

            $telegram->sendMessage($message, $division);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi Telegram tiket selesai: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Tiket berhasil dikonfirmasi Selesai (DONE) pada tanggal ' . $completedAt->format('d/m/Y H:i') . '.');
    }

    /**
     * Menampilkan Dashboard SLA & Ticket Trend dengan Data Riil Database
     */
    public function slaDashboard()
    {
        $itCount = Ticket::where('department', 'IT')->count();
        $maintenanceCount = Ticket::where('department', 'MAINTENANCE')->count();
        $totalTickets = $itCount + $maintenanceCount;

        $itPriorities = [
            'Rendah' => Ticket::where('department', 'IT')->where('priority', 'Rendah')->count(),
            'Sedang' => Ticket::where('department', 'IT')->where('priority', 'Sedang')->count(),
            'Tinggi' => Ticket::where('department', 'IT')->where('priority', 'Tinggi')->count(),
        ];

        $maintPriorities = [
            'Rendah' => Ticket::where('department', 'MAINTENANCE')->where('priority', 'Rendah')->count(),
            'Sedang' => Ticket::where('department', 'MAINTENANCE')->where('priority', 'Sedang')->count(),
            'Tinggi' => Ticket::where('department', 'MAINTENANCE')->where('priority', 'Tinggi')->count(),
        ];

        $itCompleted = Ticket::where('department', 'IT')->where(function($q) {
            $q->whereIn('status', ['Selesai', 'Completed'])->orWhere('work_status', 'Completed');
        })->count();
        $itClosureRate = $itCount > 0 ? round(($itCompleted / $itCount) * 100) : 0;

        $maintCompleted = Ticket::where('department', 'MAINTENANCE')->where(function($q) {
            $q->whereIn('status', ['Selesai', 'Completed'])->orWhere('work_status', 'Completed');
        })->count();
        $maintClosureRate = $maintenanceCount > 0 ? round(($maintCompleted / $maintenanceCount) * 100) : 0;

        $dailyTrends = Ticket::selectRaw('DATE(created_at) as date, department, count(*) as count')
            ->groupBy('date', 'department')
            ->orderBy('date', 'ASC')
            ->limit(20)
            ->get();

        return view('tickets.sla_dashboard', compact(
            'totalTickets', 
            'itCount', 
            'maintenanceCount', 
            'itPriorities', 
            'maintPriorities', 
            'itClosureRate', 
            'maintClosureRate', 
            'dailyTrends'
        ));
    }
}