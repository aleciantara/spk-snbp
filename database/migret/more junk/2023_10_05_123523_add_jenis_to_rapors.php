<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisToRapors extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rapors', function($table) {
            $table->enum('jenis', ['wajib', 'peminatan'])->default('wajib')->after('pelajaran');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('rapors', function($table) {
            $table->dropColumn('jenis');
        });
    }
};
