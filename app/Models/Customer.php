<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

class Customer extends Model
{
    use HasFactory;

    protected $casts = [
        'is_favourite'=> 'boolean'
    ];

    protected $fillable = ['name','tel','is_favourite'];
    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m');
    }
}
