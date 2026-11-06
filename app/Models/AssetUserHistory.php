<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetUserHistory extends Model
{
    use HasFactory;

    protected $table = 'asset_user_histories';

    protected $fillable = [
        'asset_id',    // <-- Tambahkan baris ini
        'usage_date',
        'asset_code',
        'location',
        'old_user',
        'new_user',
        'description',
        'officer',
    ];

    public function asset()
    {
        return $this->belongsTo(AssetManagement::class, 'asset_id');
    }
}