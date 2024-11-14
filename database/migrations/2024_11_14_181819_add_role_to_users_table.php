<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Añadimos la columna `role` con valor por defecto `trabajador`
            $table->string('role')->default('trabajador');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminamos la columna `role` si se hace un rollback
            $table->dropColumn('role');
        });
    }
}