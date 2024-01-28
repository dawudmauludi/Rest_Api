<?php

namespace App\Models;

use Database\Seeders\VaccinesSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spots extends Model
{
    use HasFactory;
    protected $fillable = [
        'regional_id',
        'name', 
        'address', 
        'serve',
        'capacity'
    ];



    public $timestaps;
    public function regional() {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    // public function vaccination(){
    //     return $this->hasMany(Vaccination::class, 'spot_id');
    // }

    public function vaccines(){
        return $this->hasMany(SpotVaccines::class, 'spot_id');
    }

    public function SpotVaccines($id, $name)
    {
        $vaccines = SpotVaccines::where('spot_id', $id)->with('vaccines')->get();
        foreach ($vaccines as $key => $value) {
            if ($value->vaccines->name == $name)return true; 
                return false;
            }
         
            
}


}
