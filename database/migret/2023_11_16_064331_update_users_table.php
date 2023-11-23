<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user', 'siswa'])->change();

            // Add 'nisn' foreign key column
            $table->bigInteger('nisn');
            $table->foreign('nisn')->references('nisn')->on('siswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop 'role' column
            $table->enum('role', ['admin', 'user'])->change();

            // Drop 'nisn' foreign key column
            $table->dropForeign(['nisn']);
            $table->dropColumn('nisn');
        });
    }
}
