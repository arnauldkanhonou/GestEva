<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;
    protected $fillable = ['libelle','dateFormation','objectifVise','annee_id','programmer','type_formation_id'];
    public $timestamps = false;
    protected $casts = ['programmer'=>'boolean'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function employes(){
        return $this->belongsToMany(Employe::class,'formation_employe','formation_id','employe_id')->withPivot('formation_id','employe_id')->as('formationsEmploye')->withTimestamps();
    }

    public function type_formation(){
        return $this->belongsTo(TypeFormation::class);
    }

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m');
    }
}
