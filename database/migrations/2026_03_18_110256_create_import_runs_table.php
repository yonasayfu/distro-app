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
        Schema::create('import_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('resource');
            $table->string('status');
            $table->string('file_name');
            $table->unsignedInteger('rows_count')->default(0);
            $table->unsignedInteger('valid_rows_count')->default(0);
            $table->unsignedInteger('imported_rows_count')->default(0);
            $table->json('summary')->nullable();
            $table->json('preview_rows')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_runs');
    }
};
