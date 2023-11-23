<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBobotKriteriasTable extends Migration
{
    public function up()
    {
        Schema::create('bobot_kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria', 255);
            $table->float('bobot');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bobot_kriterias');
    }
}
