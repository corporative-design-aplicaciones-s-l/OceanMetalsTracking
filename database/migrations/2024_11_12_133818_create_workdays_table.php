<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkdaysTable extends Migration
{
    public function up()
    {
        Schema::create('workdays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
            $table->date('date'); // Fecha de la jornada
            $table->time('start_time'); // Hora de inicio
            $table->time('end_time')->nullable(); // Hora de fin
            $table->integer('break_minutes')->default(0); // Minutos de descanso
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workdays');
    }
}