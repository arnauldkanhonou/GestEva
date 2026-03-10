<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampagneObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campagne_objectifs', function (Blueprint $table) {
            $table->id();
            $table->string('libelle',80);
            $table->boolean('cloturer')->default(false);
            $table->dateTime('plage1');
            $table->dateTime('plage2');
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
        Schema::dropIfExists('campagne_objectifs');
    }
}
