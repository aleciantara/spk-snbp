<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestasisTable extends Migration

{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->integer('nisn');
            $table->string('judul');
            $table->string('jenis');
            $table->string('tingkat');
            $table->string('juara');
            $table->string('gambar_path')->nullable(); 
            $table->timestamps();
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
