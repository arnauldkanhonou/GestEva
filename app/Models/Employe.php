<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    protected $guarded = [];

    protected $casts = [
        'respUnite'=> 'boolean',
        'respService'=> 'boolean',
        'respDepartement'=> 'boolean',
        'isDirecteur'=> 'boolean',
        'hasRespDepart'=> 'boolean',
        'hasCollab'=> 'boolean',
    ];

    public function objectif(){
        return $this->hasMany(Objectif::class);
    }

    public function fonction(){
        return $this->belongsTo(Fonction::class);
    }

    public function categorie_professionnelle(){
        return $this->belongsTo(CategorieProfessionnelle::class);
    }

    public function direction(){
        return $this->belongsTo(Direction::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function unite(){
        return $this->belongsTo(Unite::class);
    }
    /*public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m');
    }*/
}
