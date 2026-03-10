<?php

namespace App\Models;

use App\Http\Resources\GlobalResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unite extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function formations(){
        return $this->belongsToMany(Formation::class,'formation_unites','formation_id','unite_id')->withPivot('id','formation_id','unite_id')->as('formationUnite')->withTimestamps();
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function formationsAnneePrecedent(){
        $tabFormation = array();
        $date = (new \DateTime())->format('Y')-1;
        $annee = Annee::where('libelle',$date)->first();
        foreach ($this->formations as $formation){
            if ($formation->annee_id==$annee->id){
                array_push($tabFormation,$formation);
            }
        }

        return GlobalResource::collection($tabFormation);
    }
}
