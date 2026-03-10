<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('dateEvaluation');
            $table->dateTime('dateEntretien');
            $table->text('accomplissement');
            $table->text('difficulteMission');
            $table->text('progres');
            $table->text('AvenirProfs');
            $table->text('convenanceMission');
            $table->text('difficulteGblobal');
            $table->text('superCompetence');
            $table->text('commentaireEvaluer');
            $table->text('commentaireResp');
            $table->foreignId('annee_id')->constrained();
            $table->foreignId('type_evaluation_id')->constrained();
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
        Schema::dropIfExists('evaluations');
    }
}
