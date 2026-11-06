@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header & Navigasi Tab Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard SLA & Ticket Trend</h2>
            <p class="text-muted small mb-0">Analisis tren pembuatan tiket, prioritas, dan persentase penyelesaian SLA secara real-time.</p>
        </div>
        <div class="btn-group shadow-sm" role="group">
            <a href="#" class="btn btn-primary btn-sm fw-bold active">Ticket Trend</a>
            <a href="#" class="btn btn-outline-primary btn-sm fw-bold">SLA Dashboard</a>
        </div>
    </div>

    <!-- Baris Pertama: Grafik Tren Harian & Total Tiket -->
    <div class="row g-4 mb-4">
        <!-- Grafik Tren Pembuatan Tiket Harian (Line Chart) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold fs-6">Daily Ticket Creation Trend</h5>
                    <span class="badge bg-light text-dark border">Bulan Berjalan</span>
                </div>
                <div class="card-body">
                    <div style="height: 280px;">
                        <canvas id="dailyTicketChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nbr of Tickets - Total (IT vs MAINTENANCE) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold fs-6">Total Tiket</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    @php
                        $totalAll = ($itCount ?? 0) + ($maintenanceCount ?? 0);
                        $itPercent = $totalAll > 0 ? round((($itCount ?? 0) / $totalAll) * 100) : 0;
                        $maintPercent = $totalAll > 0 ? round((($maintenanceCount ?? 0) / $totalAll) * 100) : 0;
                    @endphp

                    <!-- Baris IT -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>IT</span>
                            <span class="text-primary">{{ number_format($itCount ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $itPercent }}%;" aria-valuenow="{{ $itPercent }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $itCount ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <!-- Baris Maintenance -->
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>MAINTENANCE</span>
                            <span class="text-success">{{ number_format($maintenanceCount ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $maintPercent }}%;" aria-valuenow="{{ $maintPercent }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $maintenanceCount ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <!-- Legenda Keterangan -->
                    <div class="mt-4 pt-3 border-top d-flex justify-content-around text-center small text-muted">
                        <div><i class="bi bi-square-fill text-primary me-1"></i> IT</div>
                        <div><i class="bi bi-square-fill text-success me-1"></i> MAINTENANCE</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Kedua: Berdasarkan Prioritas, Resolution Code, dan SLA Closure (Donut/Pie) -->
    <div class="row g-4">
        <!-- Kolom Kiri: Prioritas & Top Resolution Code -->
        <div class="col-lg-5">
            <!-- Berdasarkan Prioritas & Departemen -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold fs-6">Prioritas Tiket</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>IT</span>
                            <span>{{ $itCount ?? 0 }}</span>
                        </div>
                        <div class="progress stacked-progress" style="height: 18px;">
                            <div class="progress-bar bg-success" style="width: 50%" title="Rendah"></div>
                            <div class="progress-bar bg-warning" style="width: 30%" title="Sedang"></div>
                            <div class="progress-bar bg-danger" style="width: 20%" title="Tinggi"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>MAINTENANCE</span>
                            <span>{{ $maintenanceCount ?? 0 }}</span>
                        </div>
                        <div class="progress stacked-progress" style="height: 18px;">
                            <div class="progress-bar bg-success" style="width: 60%" title="Rendah"></div>
                            <div class="progress-bar bg-warning" style="width: 25%" title="Sedang"></div>
                            <div class="progress-bar bg-danger" style="width: 15%" title="Tinggi"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3 text-muted" style="font-size: 0.75rem;">
                        <span><i class="bi bi-square-fill text-success"></i> Rendah</span>
                        <span><i class="bi bi-square-fill text-warning"></i> Sedang</span>
                        <span><i class="bi bi-square-fill text-danger"></i> Tinggi</span>
                    </div>
                </div>
            </div>

            <!-- Top 5 Resolution Code - Incident -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold fs-6">Top 5 Resolution Code - Incident</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <div class="d-flex justify-content-between mb-1"><span class="fw-bold">CONFIGURATION</span> <span>1,935</span></div>
                            <div class="progress" style="height: 6px;"><div class="progress-bar bg-primary" style="width: 90%;"></div></div>
                        </li>
                        <li class="mb-2">
                            <div class="d-flex justify-content-between mb-1"><span class="fw-bold">NO ACTION TAKEN</span> <span>712</span></div>
                            <div class="progress" style="height: 6px;"><div class="progress-bar bg-primary" style="width: 45%;"></div></div>
                        </li>
                        <li class="mb-2">
                            <div class="d-flex justify-content-between mb-1"><span class="fw-bold">SECURITY PWR REBOOT</span> <span>257</span></div>
                            <div class="progress" style="height: 6px;"><div class="progress-bar bg-primary" style="width: 20%;"></div></div>
                        </li>
                        <li>
                            <div class="d-flex justify-content-between mb-1"><span class="fw-bold">SECURITY ACCESS FIX</span> <span>249</span></div>
                            <div class="progress" style="height: 6px;"><div class="progress-bar bg-primary" style="width: 18%;"></div></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ticket Closure SLA (Donut Charts) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold fs-6">Ticket Closure & SLA Compliance</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-around">
                    <div class="row text-center">
                        <!-- IT Closure Rate -->
                        <div class="col-md-6 position-relative mb-4 mb-md-0">
                            <h6 class="text-muted fw-bold mb-3">IT Closure</h6>
                            <div style="height: 180px; position: relative;">
                                <canvas id="itClosureChart"></canvas>
                                <div class="position-absolute top-50 start-50 translate-middle fw-bold fs-4 text-primary">
                                    {{ $itClosureRate ?? 0 }}%
                                </div>
                            </div>
                        </div>
                        <!-- Maintenance Closure Rate -->
                        <div class="col-md-6 position-relative">
                            <h6 class="text-muted fw-bold mb-3">Maintenance Closure</h6>
                            <div style="height: 180px; position: relative;">
                                <canvas id="maintClosureChart"></canvas>
                                <div class="position-absolute top-50 start-50 translate-middle fw-bold fs-4 text-success">
                                    {{ $maintClosureRate ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan Status Legend Sesuai Sistem Tiket -->
                    <div class="row mt-4 pt-3 border-top text-center" style="font-size: 0.75rem;">
                        <div class="col"><span class="badge bg-secondary rounded-circle p-1 me-1"></span> Terbuka</div>
                        <div class="col"><span class="badge bg-info rounded-circle p-1 me-1"></span> On Check</div>
                        <div class="col"><span class="badge bg-warning rounded-circle p-1 me-1"></span> On Progress</div>
                        <div class="col"><span class="badge bg-primary rounded-circle p-1 me-1"></span> Menunggu Konfirmasi</div>
                        <div class="col"><span class="badge bg-success rounded-circle p-1 me-1"></span> Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sertakan pustaka Chart.js untuk merender grafik interaktif -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Tren Harian (Line Chart) - IT (Biru) & MAINTENANCE (Hijau)
    const ctxDaily = document.getElementById('dailyTicketChart').getContext('2d');
    new Chart(ctxDaily, {
        type: 'line',
        data: {
            labels: ['Feb 25', 'Feb 28', 'Mar 2', 'Mar 5', 'Mar 7', 'Mar 10', 'Mar 12', 'Mar 15', 'Mar 18', 'Mar 22', 'Mar 25', 'Mar 27'],
            datasets: [{
                label: 'IT',
                data: [187, 174, 191, 180, 145, 170, 148, 200, 166, 212, 197, 16],
                borderColor: '#0d6efd', // Warna Biru untuk IT
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                tension: 0.2,
                fill: true
            }, {
                label: 'MAINTENANCE',
                data: [127, 205, 30, 129, 6, 109, 8, 147, 11, 247, 141, 9],
                borderColor: '#198754', // Warna Hijau untuk Maintenance
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 2,
                tension: 0.2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom', 
                    labels: { boxWidth: 12 } 
                } 
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Donut Chart IT Closure / Compliance
    const ctxIt = document.getElementById('itClosureChart').getContext('2d');
    newChart(ctxIt, 'doughnut', [{{ $itClosureRate ?? 0 }}, {{ 100 - ($itClosureRate ?? 0) }}], ['#0d6efd', '#e9ecef']);

    // Donut Chart Maintenance Closure / Compliance
    const ctxMaint = document.getElementById('maintClosureChart').getContext('2d');
    newChart(ctxMaint, 'doughnut', [{{ $maintClosureRate ?? 0 }}, {{ 100 - ($maintClosureRate ?? 0) }}], ['#198754', '#e9ecef']);

    function newChart(context, type, dataValues, bgColors) {
        return new Chart(context, {
            type: type,
            data: {
                datasets: [{
                    data: dataValues,
                    backgroundColor: bgColors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false }, tooltip: { enabled: false } }
            }
        });
    }
</script>
@endsection