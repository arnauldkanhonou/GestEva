<?php

use App\Models\CritereEvaluation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritereEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critere_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('code',5)->unique();
            $table->string('libelle',100)->unique();
            $table->foreignIdFor(CritereEvaluation::class);
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
        Schema::dropIfExists('critere_evaluations');
    }
}
