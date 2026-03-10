<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnValiderToLaureatBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laureat_evaluations', function (Blueprint $table) {
            $table->boolean('valider')->default(false);
            $table->decimal('totalSMCperfA',18,2)->nullable();
            $table->decimal('totalSMCperfB',18,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laureat_bonus', function (Blueprint $table) {
            //
        });
    }
}
