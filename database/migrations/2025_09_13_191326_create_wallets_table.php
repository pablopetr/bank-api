<?php

use App\Enums\WalletStatus;
use App\Enums\WalletType;
use App\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('balance', 15, 2)->default(0);
            $table->enum('type', WalletType::values())->default(WalletType::Default->value);
            $table->enum('status', WalletStatus::values())->default(WalletStatus::Active->value);
            $table->foreignIdFor(Account::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
