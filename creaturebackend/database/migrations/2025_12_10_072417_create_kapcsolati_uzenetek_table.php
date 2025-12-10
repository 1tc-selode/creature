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
        Schema::create('kapcsolati_uzenetek', function (Blueprint $table) {
            $table->id();
            $table->string('nev', 200);
            $table->string('email', 255);
            $table->string('telefon', 20)->nullable();
            $table->string('targy', 300);
            $table->text('uzenet');
            $table->enum('tipus', ['általános', 'lény_bejelentés', 'hiba_jelentés', 'javaslat', 'egyéb'])->default('általános');
            $table->foreignId('leny_id')->nullable()->constrained('lenyek')->onDelete('set null'); // Mely lényhez kapcsolódik
            $table->enum('allapot', ['új', 'feldolgozás_alatt', 'válaszolt', 'lezárt'])->default('új');
            $table->text('admin_megjegyzes')->nullable();
            $table->string('ip_cim', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['allapot', 'created_at']);
            $table->index('tipus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapcsolati_uzenetek');
    }
};
