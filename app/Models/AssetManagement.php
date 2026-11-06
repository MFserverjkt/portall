<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetManagement extends Model
{
    use HasFactory;

    protected $table = 'assets_management';

    protected $fillable = [
        'purchase_date',
        'asset_name',
        'asset_code',
        'status_asset',
        'in_use_date',
        'condition',
    ];

    public function userHistories()
    {
        return $this->hasMany(AssetUserHistory::class, 'asset_id');
    }

    public function maintenanceHistories()
    {
        return $this->hasMany(AssetMaintenanceHistory::class, 'asset_id');
    }
}