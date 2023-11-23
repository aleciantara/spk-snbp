<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkKriteriasTable extends Migration
{
    public function up()
    {
        Schema::create('spk_kriterias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nisn');
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
            $table->float('rapor')->nullable(); // Assuming the average value is stored as a decimal
            $table->float('prestasi')->nullable();
            $table->float('sikap')->nullable(); // Assuming sikap value is an integer from 1 to 5
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spk_kriterias');
    }
}
