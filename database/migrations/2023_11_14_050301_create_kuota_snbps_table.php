<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKuotaSnbpsTable extends Migration
{
    public function up()
    {
        Schema::create('kuota_snbps', function (Blueprint $table) {
            $table->id();
            $table->string('peminatan', 255);
            $table->integer('kuota');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kuota_snbps');
    }
}
