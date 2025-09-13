<?php

use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('status', UserStatus::values())->default(UserStatus::WaitingForApproval->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_users');
    }
};
