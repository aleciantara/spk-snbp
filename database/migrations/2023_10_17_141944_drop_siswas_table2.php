<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSiswasTable2 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() 
    {
        Schema::dropIfExists('siswas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
