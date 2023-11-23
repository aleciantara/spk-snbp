<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeminatanToSiswas extends Migration
{
    public function up()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->enum('peminatan', ['MIPA', 'IPS'])->default('MIPA')->after('kelas_12');
        });
    }

    public function down()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('peminatan');
        });
    }
}