<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    public $timestamps;

    protected $fillable = [
        'dose', 
        'date', 
        'society_id', 
        'spot_id', 
        'vaccine_id', 
        'doctor_id', 
        'officer_id'
    ];

    public function spots() {
        return $this->belongsTo(Spots::class, 'spot_id');
    }

    public function society() {
        return $this->hasMany(society::class, 'society_id');
    }

    public function vaccines() {
        return $this->hasOne(Vaccines::class, 'id','vaccine_id');
    }

    public function vaccinator() {
        return $this->hasOne(Medical::class, 'id','doctor_id');
    }
    // public function officer() {
    //     return $this->belongsTo(Medical::class, 'officer_id');
    // }
}
