<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['transmis'=>'boolean','clotureEvaluer'=>'boolean','clotureResp'=>'boolean'];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-m-d H:i:s';
    }

    public function formation(){
        return $this->belongsToMany(Formation::class,'suivre','evaluation_id','formation_id')->withPivot('appreciation','date','objectifVise')->as('formationSuivie')->withTimestamps();
    }

    public function critereEvaluer(){
        return $this->belongsToMany(CritereEvaluation::class,'critere_evaluers','evaluation_id','critere_evaluation_id')->withPivot('apprEvaluer','pointEvaluer','apprResp','pointResp','apprFinal','pointFinal')->as('critereEvaluer')->withTimestamps();
    }

    public function annee(){
        return $this->belongsTo(Annee::class);
    }

    public function employe(){
        return $this->belongsTo(Employe::class);
    }

    /*public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-m-d');
    }*/
}
