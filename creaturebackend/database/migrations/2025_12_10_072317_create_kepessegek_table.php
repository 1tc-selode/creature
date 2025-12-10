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
        Schema::create('kepessegek', function (Blueprint $table) {
            $table->id();
            $table->string('nev', 150);
            $table->text('leiras')->nullable();
            $table->enum('tipus', ['fizikai', 'mágikus', 'mentális', 'speciális'])->default('speciális');
            $table->integer('erosseg')->default(1)->comment('1-10 skála');
            $table->integer('ritkasag')->default(1)->comment('1-10 skála');
            $table->boolean('aktiv')->default(true);
            $table->timestamps();
            
            $table->index('tipus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepessegek');
    }
};
