<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddConstraindToTableCritereEvaluation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('critere_evaluations', function (Blueprint $table) {
            $table->dropColumn('critere_evaluation_id');
            $table->foreignId('categorie_critere_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('critere_evaluations', function (Blueprint $table) {
            //
        });
    }
}
