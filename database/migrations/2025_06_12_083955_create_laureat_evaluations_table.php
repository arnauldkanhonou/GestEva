<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaureatEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laureat_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('matricule');
            $table->string('salarie');
            $table->string('poste');
            $table->string('categorie');
            $table->decimal('notePonderer',18,2);
            $table->string('PerfApresPondereration',5);
            $table->decimal('smc',18,2)->nullable();
            $table->decimal('montantAppliquer',18,2)->nullable();
            $table->decimal('montantBonus',18,2)->nullable();
            $table->decimal('tauxPerfA',18,2)->nullable();
            $table->decimal('tauxPerfB',18,2)->nullable();
            $table->decimal('tauxResultat',18,2)->nullable();
            $table->integer('sommeSB')->nullable();
            $table->integer('budget')->nullable();
            $table->decimal('montantTroisPercent',18,3)->nullable();
            $table->decimal('montantCagnotte',18,3)->nullable();
            $table->boolean('isPrimeExcept')->default(false);
            $table->foreignId('annee_id')->constrained();
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
        Schema::dropIfExists('laureat_evaluations');
    }
}
