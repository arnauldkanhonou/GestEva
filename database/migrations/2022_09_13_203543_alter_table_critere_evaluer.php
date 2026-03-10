<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableCritereEvaluer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('critere_evaluers', function (Blueprint $table) {
            DB::statement("ALTER TABLE critere_evaluers ALTER COLUMN apprEvaluer VARCHAR(255)  null;");
            DB::statement("ALTER TABLE critere_evaluers ALTER COLUMN pointEvaluer int null;");
            DB::statement("ALTER TABLE critere_evaluers ALTER COLUMN apprResp VARCHAR(255) null;");
            DB::statement("ALTER TABLE critere_evaluers ALTER COLUMN pointResp int null;");
            DB::statement("ALTER TABLE critere_evaluers ALTER COLUMN apprFinal VARCHAR(255) null;");
            DB::statement("ALTER TABLE critere_evaluers ALTER COLUMN pointFinal int null;");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('critere_evaluers', function (Blueprint $table) {
            //
        });
    }
}
