<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeReciboNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('CAT_RECIBO', function(Blueprint $table)
        {
            $table->binary('RECIBO')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('CAT_RECIBO', function(Blueprint $table)
        {
            $table->binary('RECIBO')->change();
        });
    }
}
