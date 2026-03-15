<?php

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
        DB::table('applications')
            ->where('status', 'submitted')
            ->update(['status' => 'pending']);

        Schema::table('applications', function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_at', 'status']);
        });

        DB::statement("
            ALTER TABLE applications
            MODIFY status ENUM('pending', 'under_review', 'approved', 'rejected')
            NOT NULL DEFAULT 'pending'
        ");

        Schema::table('applications', function (Blueprint $table): void {
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('applications')
            ->where('status', 'pending')
            ->update(['status' => 'submitted']);

        Schema::table('applications', function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_at', 'status']);
        });

        DB::statement("
            ALTER TABLE applications
            MODIFY status ENUM('submitted', 'under_review', 'approved', 'rejected')
            NOT NULL DEFAULT 'submitted'
        ");

        Schema::table('applications', function (Blueprint $table): void {
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['created_at', 'status']);
        });
    }
};
