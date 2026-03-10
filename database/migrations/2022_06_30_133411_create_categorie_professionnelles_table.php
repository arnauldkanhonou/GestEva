<?php

use App\Models\CategorieEmploye;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorieProfessionnellesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorie_professionnelles', function (Blueprint $table) {
            $table->id();
            $table->string('code',2)->unique();
            $table->string('libelle')->unique();
            $table->foreignIdFor(CategorieEmploye::class);
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
        Schema::dropIfExists('categorie_professionnelles');
    }
}
