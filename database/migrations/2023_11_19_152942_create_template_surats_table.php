<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateSuratsTable extends Migration
{
    public function up()
    {
        Schema::create('template_surats', function (Blueprint $table) {
            $table->id();
            $table->string('surat');
            $table->string('file'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('template_surats');
    }
}
