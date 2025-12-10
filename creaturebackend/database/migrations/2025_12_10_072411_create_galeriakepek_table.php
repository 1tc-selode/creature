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
        Schema::create('galeriakepek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leny_id')->constrained('lenyek')->onDelete('cascade');
            $table->string('eredeti_nev', 255);
            $table->string('fajl_nev', 255); // Storage-ban tárolt fájlnév
            $table->string('fajl_utvonal', 500);
            $table->string('mime_tipus', 100);
            $table->unsignedBigInteger('fajl_meret'); // bájtban
            $table->text('leiras')->nullable();
            $table->integer('sorrend')->default(0);
            $table->boolean('fo_kep')->default(false); // Főkép-e
            $table->timestamps();
            
            $table->index(['leny_id', 'sorrend']);
            $table->index('fo_kep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeriakepek');
    }
};
