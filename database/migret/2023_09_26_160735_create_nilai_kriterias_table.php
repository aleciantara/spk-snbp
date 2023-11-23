<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiKriteriasTable extends Migration
{
    public function up()
    {
        Schema::create('nilai_kriterias', function (Blueprint $table) {
            $table->id();
            $table->integer('nisn');
            $table->float('rata_rapor');
            $table->float('prestasi');
            $table->float('sikap');
            $table->timestamps();
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_kriterias');
    }
}