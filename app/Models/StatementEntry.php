<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Model;

class StatementEntry extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'statement_entries';

    protected $fillable = [
        'wallet_id',
        'direction',
        'amount',
        'remaining_balance',
        'idempotency_key',
        'posted_at',
        'source',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::updating(fn () => false);
    }

    public function scopeForWallet(Builder $query, int $walletId): Builder
    {
        return $query->where('wallet_id', $walletId);
    }

    public function scopeCredits(Builder $query): Builder
    {
        return $query->where('direction', 'credit');
    }

    public function scopeDebits(Builder $query): Builder
    {
        return $query->where('direction', 'debit');
    }
}
