<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewRaporsTable2 extends Migration
{
    public function up()
    {
        Schema::create('rapors', function (Blueprint $table) {
            $table->id();
            $table->string('nisn');
            $table->string('pelajaran');
            $table->string('jenis');
            $table->float('sem_1_nilai_p')->nullable();
            $table->float('sem_1_nilai_k')->nullable();
            $table->float('sem_2_nilai_p')->nullable();
            $table->float('sem_2_nilai_k')->nullable();
            $table->float('sem_3_nilai_p')->nullable();
            $table->float('sem_3_nilai_k')->nullable();
            $table->float('sem_4_nilai_p')->nullable();
            $table->float('sem_4_nilai_k')->nullable();
            $table->float('sem_5_nilai_p')->nullable();
            $table->float('sem_5_nilai_k')->nullable();
            $table->float('rata_nilai_p')->nullable();
            $table->float('rata_nilai_k')->nullable();
            $table->timestamps();
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rapors');
    }
}

