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
        Schema::create('lenyek', function (Blueprint $table) {
            $table->id();
            $table->string('nev', 200);
            $table->string('tudomanyos_nev', 300)->nullable();
            $table->text('leiras');
            $table->text('elohely')->nullable();
            $table->enum('meret', ['apró', 'kicsi', 'közepes', 'nagy', 'hatalmas', 'gigantikus'])->default('közepes');
            $table->enum('veszelyesseg', ['ártalmatlan', 'alacsony', 'közepes', 'magas', 'extrém'])->default('alacsony');
            $table->integer('ritkasag')->default(1)->comment('1-10 skála');
            $table->date('felfedezes_datuma')->nullable();
            $table->string('felfedezo', 200)->nullable();
            $table->enum('allapot', ['aktív', 'inaktív', 'kivizsgálás_alatt', 'eltűnt'])->default('aktív');
            $table->json('extra_adatok')->nullable(); // JSON mező különleges adatoknak
            $table->string('kep_url')->nullable(); // Fő kép
            
            // Foreign key relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kategoria_id')->constrained('kategoriak')->onDelete('cascade');
            
            $table->timestamps();
            
            // Indexek
            $table->index(['meret', 'veszelyesseg']);
            $table->index('ritkasag');
            $table->index('allapot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lenyek');
    }
};
