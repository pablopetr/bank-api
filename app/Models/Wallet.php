<?php

namespace App\Models;

use App\Enums\WalletType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $balance
 * @property WalletType $type
 * @property int $account_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @method static \Database\Factories\WalletFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereUpdatedAt($value)
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallet withoutTrashed()
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const DEFAULT_WALLET_NAME = 'Default Wallet';

    protected $fillable = [
        'name',
        'balance',
        'type',
        'account_id',
    ];

    protected $casts = [
        'type' => WalletType::class,
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
