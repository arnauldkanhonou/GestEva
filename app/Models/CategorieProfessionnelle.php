<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieProfessionnelle extends Model
{
    use HasFactory;
    protected $fillable = ['code','libelle','categorie_employe_id','smc'];
    //public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function categorie(){
        return $this->belongsTo(CategorieEmploye::class,'categorie_employe_id');
    }
    /*public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m');
    }*/

}
