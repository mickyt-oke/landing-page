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
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->nullable();

            $table->string('document_type', 80)->index();
            $table->string('original_name');
            $table->string('stored_name')->unique();
            $table->string('storage_disk', 40)->default('local');
            $table->string('storage_path');
            $table->string('mime_type', 120);
            $table->unsignedBigInteger('size_bytes');

            $table->timestamps();

            $table->index(['application_id', 'document_type']);
        });

        // FK intentionally deferred due to migration timestamp collision; relationship enforced at app layer for now.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
