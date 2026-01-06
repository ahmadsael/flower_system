<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerWallet extends Model
{
    protected $fillable = [
        'farmer_id',
        'total_amount',
        'available_amount',
        'pending_withdrawal_amount',
        'last_transaction_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'available_amount' => 'decimal:2',
            'pending_withdrawal_amount' => 'decimal:2',
            'last_transaction_at' => 'datetime',
        ];
    }

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function withdrawals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FarmerWithdrawal::class);
    }
}
