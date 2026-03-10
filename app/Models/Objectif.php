<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory;

    protected $fillable = ['libelle','ignorer','valider','employe_id','resultatAttendu','echeance','actionCle'];

    protected $guarded = [];

    protected $casts=['valider'=>'boolean','isjson'=>'boolean'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function annee(){
        return $this->belongsTo(Annee::class);
    }

    public function employe(){
        return $this->belongsTo(Employe::class);
    }
}
