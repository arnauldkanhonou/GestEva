<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTableObjectifs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            $table->dropColumn('envoye');
            $table->string('resultatAttendu')->nullable(true);
            $table->date('echeance')->nullable(true);
            $table->foreignId('annee_id')->constrained();
            $table->string('apprEvaluer',50)->nullable(true);
            $table->string('appreResp',50)->nullable(true);
            $table->string('appreFinal',50)->nullable(true);
            $table->string('niveauExecEvaluer',50)->nullable(true);
            $table->integer('noteEvaluer')->nullable(true);
            $table->string('niveauExecResp',50)->nullable(true);
            $table->integer('noteResp')->nullable(true);
            $table->string('niveauExecFinal',50)->nullable(true);
            $table->integer('noteObtenue')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            //
        });
    }
}
