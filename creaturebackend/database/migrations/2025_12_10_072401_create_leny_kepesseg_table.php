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
        Schema::create('leny_kepesseg', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leny_id')->constrained('lenyek')->onDelete('cascade');
            $table->foreignId('kepesseg_id')->constrained('kepessegek')->onDelete('cascade');
            $table->integer('szint')->default(1)->comment('A képesség szintje ennél a lénynél 1-10');
            $table->text('megjegyzes')->nullable();
            $table->timestamps();
            
            // Unique constraint - egy lénynek csak egy azonos képessége lehet
            $table->unique(['leny_id', 'kepesseg_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leny_kepesseg');
    }
};
