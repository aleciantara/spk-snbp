<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratsTable extends Migration
{
    public function up()
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nisn');
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
            $table->string('judul');
            $table->text('catatan'); 
            $table->enum('status', ['verified', 'unverified', 'denied'])->default('unverified');
            $table->string('file');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surats');
    }
}
