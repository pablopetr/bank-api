<?php

namespace App\Events;

use App\Models\IndividualUser;
use App\Models\OrganizationUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public IndividualUser|OrganizationUser $user)
    {
    }
}
