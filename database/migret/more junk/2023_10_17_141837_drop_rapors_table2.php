<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRaporsTable2 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() 
    {
        Schema::dropIfExists('rapors');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};