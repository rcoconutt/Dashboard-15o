<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoConsumoToDinamica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('CAT_DINAMICA', function (Blueprint $table) {
            $table->tinyInteger('tipo_consumo')->nullable();
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
            $table->dropColumn('tipo_consumo')->default(0);
        });
    }
}
