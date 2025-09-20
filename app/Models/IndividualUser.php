<?php

namespace App\Models;

use App\Enums\UserStatus;
use App\Observers\IndividualUserObserver;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

#[ObservedBy(IndividualUserObserver::class)]
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property UserStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Database\Factories\IndividualUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IndividualUser withoutTrashed()
 *
 * @mixin \Eloquent
 */
class IndividualUser extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use HasApiTokens;
    use HasFactory;
    use SoftDeletes;

    protected $guard = 'individual_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => UserStatus::class,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function accounts(): MorphMany
    {
        return $this->morphMany(Account::class, 'accountable');
    }
}
