<?php

namespace App\Models;

use App\Enums\WalletType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    public const DEFAULT_WALLET_NAME = 'My Wallet';

    protected $fillable = [
        'name',
        'balance',
        'type'
    ];

    protected $casts = [
        'type' => WalletType::class,
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
