<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaporsTable extends Migration
{
    public function up()
    {
        Schema::create('rapors', function (Blueprint $table) {
            $table->id();
            $table->integer('nisn');
            $table->enum('semester', ['1','2','3','4','5','rata-rata']);
            $table->string('pelajaran');
            $table->decimal('nilai', 5, 2)->nullable(); 
            $table->timestamps();
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rapors');
    }
}



