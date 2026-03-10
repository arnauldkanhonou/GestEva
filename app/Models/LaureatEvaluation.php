<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaureatEvaluation extends Model
{
    use HasFactory;
    protected $dateFormat = 'd-m-Y H:i:s';

    protected $guarded = [];
    protected $casts = ['isPrimeExcept'=>'boolean','valider'=>'boolean'];
}
