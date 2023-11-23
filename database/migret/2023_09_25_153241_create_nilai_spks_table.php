<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiSpksTable extends Migration
{
    public function up()
    {
        Schema::create('nilai_spks', function (Blueprint $table) {
            $table->id();
            $table->integer('nisn');
            $table->float('spk_rata_rapor');
            $table->float('spk_prestasi');
            $table->float('spk_sikap');
            $table->float('spk_total');
            $table->timestamps();
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_spks');
    }
}