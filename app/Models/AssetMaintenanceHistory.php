<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetMaintenanceHistory extends Model
{
    use HasFactory;

    protected $table = 'asset_maintenance_histories';

    protected $fillable = [
        'asset_id',   // <-- Pastikan ini ada
        'maintenance_date',
        'asset_code',
        'location',
        'user_name',
        'type',
        'component',
        'old_specification',
        'new_specification',
        'description',
        'officer',
    ];

    public function asset()
    {
        return $this->belongsTo(AssetManagement::class, 'asset_id');
    }
}