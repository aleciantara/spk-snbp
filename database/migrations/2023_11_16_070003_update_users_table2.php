<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable2 extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'user', 'siswa'])->after('email')->change();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'user'])->after('email')->change();
    });
}

}
