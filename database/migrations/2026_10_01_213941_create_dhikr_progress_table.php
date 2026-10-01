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
        Schema::create('dhikr_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dhikr_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('current_count')->default(0);
            $table->date('progress_date');
            $table->timestamps();

            $table->unique(['user_id', 'dhikr_id', 'progress_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dhikr_progress');
    }
};
