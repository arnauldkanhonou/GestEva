<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritereEvaluersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critere_evaluers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained();
            $table->foreignId('critere_evaluation_id')->constrained();
            $table->string('apprEvaluer');
            $table->integer('pointEvaluer');
            $table->string('apprResp');
            $table->integer('pointResp');
            $table->string('apprFinal');
            $table->integer('pointFinal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('critere_evaluers');
    }
}
