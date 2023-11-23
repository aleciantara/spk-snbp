<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkNormalisasisTable extends Migration
{
    public function up()
    {
        Schema::create('spk_normalisasis', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nisn');
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
            $table->float('rapor', 5, 4)->nullable(); 
            $table->float('prestasi', 5, 4)->default(0);
            $table->float('sikap')->nullable(); // Assuming sikap value is an integer from 1 to 5
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spk_normalisasis');
    }
}