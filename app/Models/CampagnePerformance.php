<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampagnePerformance extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-m-d H:i:s';
    }

    protected $guarded = [];

    protected $casts = ['cloturer'=>'boolean'];

    public function annee(){
        return $this->belongsTo(Annee::class);
    }

    public function type_evaluation(){
        return $this->belongsTo(TypeEvaluation::class);
    }

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m');
    }

}
