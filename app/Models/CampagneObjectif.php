<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampagneObjectif extends Model
{
    use HasFactory;

    protected $guarded;

    protected $casts = ['cloturer'=>'boolean'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->dateFormat = 'Y-d-m H:i:s';
    }

    public function annee(){
        return $this->belongsTo(Annee::class);
    }
}
