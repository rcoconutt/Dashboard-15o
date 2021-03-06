<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataToDinamicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('CAT_DINAMICA', function (Blueprint $table) {
            $table->integer('municipio_id');
            $table->integer('marca_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('CAT_DINAMICA', function (Blueprint $table) {
            $table->dropColumn('municipio_id');
            $table->dropColumn('marca_id');
        });
    }
}
