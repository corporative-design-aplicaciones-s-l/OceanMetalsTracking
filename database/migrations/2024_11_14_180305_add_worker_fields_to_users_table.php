<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkerFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('estado_trabajo')->default('no_trabajando'); // Estado: trabajando, descansando, no_trabajando, de_vacaciones
            $table->string('telefono')->nullable(); // Campo para el teléfono
            $table->string('last_name')->nullable(); // Campo para el teléfono
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['estado_trabajo', 'telefono', 'last_name']);
        });
    }
}