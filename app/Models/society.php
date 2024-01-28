<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class society extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_card_number', 
        'password', 
        'name', 
        'born_date', 
        'gender', 
        'address', 
        'regional_id', 
        'login_tokens'
    ];

    public function regional() {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    public $timestamps;
}
