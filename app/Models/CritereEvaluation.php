<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CritereEvaluation extends Model
{
    use HasFactory;
    protected $fillable = ['code','libelle','categorie_critere_id','categorie_employe_id'];
    //public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function categorieEmploye(){
        return $this->belongsTo(CategorieEmploye::class);
    }

    public function categorieCritere(){
        return $this->belongsTo(CategorieCritere::class);
    }
    /*public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-m-d');
    }*/
}
