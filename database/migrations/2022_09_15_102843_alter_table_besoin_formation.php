<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableBesoinFormation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('besoin_formations', function (Blueprint $table) {
            DB::statement('ALTER TABLE besoin_formations ALTER COLUMN libelle VARCHAR(255) null');
            DB::statement('ALTER TABLE besoin_formations ALTER COLUMN problemeEnonce VARCHAR(255) null');
            DB::statement('ALTER TABLE besoin_formations ALTER COLUMN resultatAttendu VARCHAR(255) null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('besoin_formations', function (Blueprint $table) {
            //
        });
    }
}
