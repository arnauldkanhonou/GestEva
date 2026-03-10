<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTableLaureat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laureat_evaluations', function (Blueprint $table) {
           $table->integer('idEval')->nullable();
           $table->decimal('noteObtenue',18,2)->nullable();
           $table->string('perfObtenue')->nullable();
           $table->string('service')->nullable();
           $table->decimal('montantPrime',18,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laureat_evaluations', function (Blueprint $table) {
            //
        });
    }
}
