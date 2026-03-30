<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->nullable()->after('name');
            $table->string('first_name')->nullable()->after('surname');
            $table->string('other_names')->nullable()->after('first_name');
            $table->string('passport_number', 32)->nullable()->unique()->after('other_names');
            $table->string('passport_type', 50)->nullable()->after('passport_number');
            $table->string('nationality', 120)->nullable()->after('passport_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'first_name',
                'other_names',
                'passport_number',
                'passport_type',
                'nationality',
            ]);
        });
    }
};
