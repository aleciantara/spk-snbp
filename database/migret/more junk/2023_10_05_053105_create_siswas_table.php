<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswasTable extends Migration
{
    public function up()
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->integer('nisn')->primary();
            $table->string('nama');
            $table->string('kelas_10');
            $table->string('kelas_11');
            $table->string('kelas_12');
            $table->enum('peminatan', ['MIPA', 'IPS'])->default('MIPA');
            $table->integer('angkatan')->default('2023');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswas');
    }
}
