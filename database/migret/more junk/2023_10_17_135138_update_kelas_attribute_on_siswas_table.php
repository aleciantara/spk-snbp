<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKelasAttributeOnSiswasTable extends Migration
{
    public function up()
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Set columns as nullable
            $table->string('kelas_10')->nullable()->change();
            $table->string('kelas_11')->nullable()->change();
            $table->string('kelas_12')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('kelas_10')->nullable(false)->change(); // Set as non-nullable
            $table->string('kelas_11')->nullable(false)->change(); // Set as non-nullable
            $table->string('kelas_12')->nullable(false)->change(); // Set as non-nullable
        });
    }
}
