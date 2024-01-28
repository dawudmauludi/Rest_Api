<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;
    public $timestamps;

    protected $fillable = [
        'society_id',
         'doctor_id', 
         'status', 
         'disease_history', 
         'current_symptoms', 
         'doctor_notes'
    ];

    
    public function society() {
        return $this->belongsTo(society::class, 'society_id');
    }
    public function medical() {
        return $this->belongsTo(medical::class, 'doctor_id');
    }



}
