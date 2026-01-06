<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerWithdrawal extends Model
{
    protected $fillable = [
        'farmer_id',
        'farmer_wallet_id',
        'amount',
        'method',
        'status',
        'reference',
        'note',
        'rejection_reason',
        'processed_by_admin_id',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FarmerWallet::class, 'farmer_wallet_id');
    }

    public function processedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'processed_by_admin_id');
    }
}


