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

    public function spot() {
        return $this->belongsTo(Spots::class, 'spot_id');
    }

    public function society() {
        return $this->hasMany(society::class, 'society_id');
    }

    public function vaccine() {
        return $this->belongsTo(Vaccines::class, 'vaccine_id');
    }

    public function doctor() {
        return $this->belongsTo(Medical::class, 'doctor_id');
    }
    // public function officer() {
    //     return $this->belongsTo(Medical::class, 'officer_id');
    // }
}
