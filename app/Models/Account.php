<?php

namespace App\Models;

use App\Enums\AccountStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'accountable_id',
        'accountable_type',
        'account_number',
        'status',
    ];

    protected $casts = [
        'status' => AccountStatus::class,
    ];

    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }
}
