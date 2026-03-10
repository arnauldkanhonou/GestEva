<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = ['valider'=>'boolean'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function annee(){
        return $this->belongsTo(Annee::class);
    }
    /*public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m');
    }*/
}
