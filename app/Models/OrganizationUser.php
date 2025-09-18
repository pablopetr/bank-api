<?php

namespace App\Models;

use App\Enums\UserStatus;
use App\Observers\OrganizationUserObserver;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

#[ObservedBy(OrganizationUserObserver::class)]
class OrganizationUser extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use HasApiTokens;
    use HasFactory;
    use SoftDeletes;

    protected $guard = 'organization_users';

    protected $fillable = [
        'name',
        'email',
        'password',
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
