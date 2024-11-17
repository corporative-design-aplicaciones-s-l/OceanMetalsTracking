<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('workdays', function (Blueprint $table) {
            $table->timestamp('break_start_time')->nullable()->after('break_minutes');
        });
    }

    public function down()
    {
        Schema::table('workdays', function (Blueprint $table) {
            $table->dropColumn('break_start_time');
        });
    }

};