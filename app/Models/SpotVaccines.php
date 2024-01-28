<?php

namespace App\Models;

use App\Models\Vaccines;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpotVaccines extends Model
{
    use HasFactory;
    public $timestamps;

    protected $guarded= [];

    public function Vaccines(){
        return $this->hasOne(Vaccines::class, 'id','vaccine_id');
    }
}
