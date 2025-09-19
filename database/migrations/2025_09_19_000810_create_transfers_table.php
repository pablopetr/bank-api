<?php

use App\Enums\TransferStatus;
use App\Enums\WalletStatus;
use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->enum('status', TransferStatus::values());
            $table->foreignIdFor(Wallet::class, 'from_wallet_id');
            $table->foreignIdFor(Wallet::class, 'to_wallet_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
