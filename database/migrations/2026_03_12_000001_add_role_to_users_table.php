<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->enum('role', [
                User::ROLE_USER,
                User::ROLE_REVIEWER,
                User::ROLE_ADMIN,
                User::ROLE_SUPERADMIN,
            ])->default(User::ROLE_USER)->after('password');

            $table->index('role');
        });

        DB::table('users')
            ->whereNull('role')
            ->update(['role' => User::ROLE_USER]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });
    }
};
