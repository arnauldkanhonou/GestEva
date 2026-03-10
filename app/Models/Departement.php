<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;
    protected $fillable = ['code','libelle','direction_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function direction(){
        return $this->belongsTo(Direction::class);
    }
    /*public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m');
    }*/
}
