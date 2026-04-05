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

        // Keep migrations portable across DB drivers (tests use sqlite in-memory).
        if (DB::getDriverName() === 'mysql') {
            $this->dropMysqlIndexIfExists('applications', 'applications_status_index');
            $this->dropMysqlIndexIfExists('applications', 'applications_created_at_status_index');

            DB::statement("
                ALTER TABLE applications
                MODIFY status ENUM('pending', 'under_review', 'approved', 'rejected')
                NOT NULL DEFAULT 'pending'
            ");
        }

        // Indexes already exist from the original create table migration; don't recreate them.
        // (Avoids sqlite "index ... already exists" during tests.)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('applications')
            ->where('status', 'pending')
            ->update(['status' => 'submitted']);

        if (DB::getDriverName() === 'mysql') {
            $this->dropMysqlIndexIfExists('applications', 'applications_status_index');
            $this->dropMysqlIndexIfExists('applications', 'applications_created_at_status_index');

            DB::statement("
                ALTER TABLE applications
                MODIFY status ENUM('submitted', 'under_review', 'approved', 'rejected')
                NOT NULL DEFAULT 'submitted'
            ");
        }

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_status_index');
            $table->dropIndex('applications_user_id_status_index');
            $table->dropIndex('applications_created_at_status_index');
        });
    }

    private function dropMysqlIndexIfExists(string $table, string $index): void
    {
        $exists = DB::select(
            "SELECT 1 FROM information_schema.statistics
             WHERE table_schema = DATABASE()
               AND table_name = ?
               AND index_name = ?
             LIMIT 1",
            [$table, $index]
        );

        if ($exists) {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$index}`");
        }
    }
};
