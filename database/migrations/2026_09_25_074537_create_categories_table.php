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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar'); //أذكار
            $table->string('name_en')->nullable(); //Athkar
            $table->string('slug')->unique(); //morning
            $table->time('starts_at')->nullable(); //4:55 AM
            $table->time('ends_at')->nullable(); //6 AM
            $table->integer('order')->default(0); //te first one -1-
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
