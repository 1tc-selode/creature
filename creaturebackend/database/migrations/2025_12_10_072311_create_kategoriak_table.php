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
        Schema::create('kategoriak', function (Blueprint $table) {
            $table->id();
            $table->string('nev', 100)->unique();
            $table->text('leiras')->nullable();
            $table->string('szin', 7)->default('#007bff'); // HEX színkód
            $table->string('ikon', 50)->nullable(); // Font Awesome ikon név
            $table->boolean('aktiv')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoriak');
    }
};
