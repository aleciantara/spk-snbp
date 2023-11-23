<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestasiSiswaTable extends Migration
{
    public function up()
    {
        Schema::create('prestasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestasi_id');
            $table->bigInteger('nisn');
            $table->foreign('prestasi_id')->references('id')->on('prestasis')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestasi_siswa');
    }
}
