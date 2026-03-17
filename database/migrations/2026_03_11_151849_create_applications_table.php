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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('application_reference', 40)->unique();
            $table->string('full_name');
            $table->string('passport_number', 32)->index();
            $table->string('nationality', 120)->index();
            $table->string('visa_category', 120)->index();
            $table->date('arrival_date');
            $table->unsignedInteger('overstay_days')->default(0);

            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])
                ->default('pending')
                ->index();

            $table->text('applicant_note')->nullable();
            $table->text('reviewer_comment')->nullable();
            $table->string('rejection_reason', 191)->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
