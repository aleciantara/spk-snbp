<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestasisTable extends Migration
{
    public function up()
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('prestasi');
            $table->string('juara');
            $table->string('tingkat');
            $table->string('penyelenggara');
            $table->dateTime('waktu');
            $table->integer('poin')->nullable;
            $table->enum('status', ['verified', 'unverified'])->default('unverified');
            $table->string('file')->nullable;
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestasis');
    }
}
