<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['code','libelle','direction_id','departement_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function departement(){
        return $this->belongsTo(Departement::class);
    }
    public function direction(){
        return $this->belongsTo(Direction::class);
    }
}
