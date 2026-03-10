<?php

use App\Models\Unite;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('matricule',5)->unique();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('sexe',10);
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->date('dateEmbauche');
            $table->date('dateNaissance')->nullable(true);
            $table->date('lieuNaissance')->nullable(true);
            $table->foreignId('fonction_id')->nullable()->constrained();
            $table->foreignId('unite_id')->nullable()->constrained();
            $table->boolean('respUnite')->default(false);
            $table->boolean('respService')->default(false);
            $table->boolean('respDepartement')->default(false);
            $table->string('pathFile')->nullable(true);
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
        Schema::dropIfExists('employes');
    }
}
