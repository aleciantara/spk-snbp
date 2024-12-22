<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewSiswasTable2 extends Migration
{
    public function up()
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->string('nisn')->primary();
            $table->string('nama');
            $table->string('kelas_10')->nullable();
            $table->string('kelas_11')->nullable();
            $table->string('kelas_12')->nullable();
            $table->enum('peminatan', ['MIPA', 'IPS'])->default('MIPA');
            $table->string('sikap')->default('Sangat Baik');
            $table->string('agama')->nullable();
            $table->integer('angkatan')->default('2024');
            $table->string('snbp')->default('Bersedia');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswas');
    }
}
