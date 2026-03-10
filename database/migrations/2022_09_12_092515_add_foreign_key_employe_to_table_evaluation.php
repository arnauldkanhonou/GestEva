<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyEmployeToTableEvaluation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->foreignId('employe_id')->constrained();
            DB::statement("ALTER TABLE evaluations ALTER COLUMN commentaireResp VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN accomplissement VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN difficulteMission VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN progres VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN AvenirProfs VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN convenanceMission VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN difficulteGblobal VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN superCompetence VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN commentaireEvaluer VARCHAR(255)  null;");
            DB::statement("ALTER TABLE evaluations ALTER COLUMN dateEntretien datetime  null;");
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            //
        });
    }
}
