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
        Schema::create('esemenyek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leny_id')->constrained('lenyek')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cim', 300);
            $table->text('leiras');
            $table->enum('tipus', ['megfigyelés', 'interakció', 'veszély', 'felfedezés', 'kutatás', 'egyéb'])->default('megfigyelés');
            $table->datetime('esemeny_datuma');
            $table->string('helyszin', 500)->nullable();
            $table->json('koordinatak')->nullable(); // GPS koordináták
            $table->enum('fontossag', ['alacsony', 'közepes', 'magas', 'kritikus'])->default('közepes');
            $table->text('tanusitva')->nullable(); // Tanúk
            $table->json('csatolmanyok')->nullable(); // Képek, videók linkjei
            $table->boolean('publikus')->default(false); // Nyilvános-e
            $table->timestamps();
            
            $table->index(['leny_id', 'esemeny_datuma']);
            $table->index(['tipus', 'fontossag']);
            $table->index('publikus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esemenyek');
    }
};
