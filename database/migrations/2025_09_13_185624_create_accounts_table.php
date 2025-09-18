<?php

use App\Enums\AccountStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP SEQUENCE IF EXISTS accounts_number_seq CASCADE');

        DB::statement('CREATE SEQUENCE accounts_number_seq START WITH 10000 INCREMENT BY 1');

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('number')
                ->default(DB::raw("nextval('accounts_number_seq')"))
                ->unique();
            $table->enum('status', AccountStatus::values())->default(AccountStatus::Active->value);
            $table->morphs('accountable');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
