<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use App\Models\society;
use App\Models\Spots;
use App\Models\SpotVaccines;
use App\Models\Vaccination;
use App\Models\Vaccines;
use Illuminate\Http\Request;

class SpotsController extends Controller
{
    protected $spots, $regional, $vaccines;
    public function __construct(Spots $spots, Regional $regional, Vaccines $vaccines)
    {
        $this->spots = $spots;
        $this->regional = $regional;
        $this->vaccines = $vaccines;
       
    }

    /**
     * Display a listing of the resource.
     */
    public function indexSpots(Request $request)
    {

        $society =society::where('login_tokens', $request->query('login_tokens'))->first();
        if(!$society){
            return Controller::failed('user tidak ada');
        }

        $vaccination= Vaccination::where('society_id', $society->id)->get()->count();
        $spots = Spots::where('regional_id', $society->regional_id)->get();
        $vaccine = Vaccines::all();

        $availableVaccine=[];

        foreach($spots as $spot){
            foreach($vaccine as $vaccines){
                $available=SpotVaccines::where([
                    'spot_id' => $spot->id,
                    'vaccine_id' => $vaccines->id
                ])->exists();
                $availableVaccine[$vaccines->name]=$available;

            }

            if(($spot->serve == 1 && $vaccination==1) || ($spot->serve == 2 && $vaccination<1)){
                $spot['Unavailable']=true;
            }
            $spot['Available_vaccine']= $availableVaccine;
        }
        return Controller::success('suksess', $spots);

    //    $spotsData = $this->spots->all();
    //    $spotsData2 = [];

    //    $vaccines =$this->vaccines->all();


     
    // //    $spotsData = Spots::where('regional_id', $spotsData->regional_id)->first();
   
    
    //     foreach($spotsData as $key => $value){
    //         $temp = collect($this->spots->findOrFail($value->id));
    //         $newVaccine = collect();
    //         foreach($vaccines as $key => $Vaccine){
    //         $newVaccine->put($Vaccine->name, $this->spots->SpotVaccines($value->id, $Vaccine->name));
    //     }
    //     $temp->put('vaccines', $newVaccine);
    //     $spotsData2[]= $temp;
    //    }
    //    return Controller::success('berhasil menampilkan data', $spotsData2);
    
    }

    //     public function SpotVaccines($id, $name)
    //     {
    //         $vaccines = SpotVaccines::where('spot_id', $id)->with('vaccines')->get();
    //         foreach ($vaccines as $key => $value) {
    //             if ($value->vaccines->name == $name)return true; 
    //                 return false;
    //     }
    // }
  

  

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showSpots($id, Request $request)
    {
        $token = $request->query('login_tokens');
        if($token == null)
        return Controller::Failed('Unauthorized user', $token);

        $tanggal = date('Y-m-d', strtotime($request->query('date')));
        $data =$this->spots->findOrFail($id);
        $data2 = collect();
        $data2->put('date', $tanggal);
        $data2->put('spots', $data);

     $count =Vaccination::where('date', $tanggal)->where('spot_id', $id)->count();
     $data2->put('vaccination_count', $count);

        if($tanggal != null){
            return Controller::success('berhasil menampilkan data', $data2);
        }

        $data =$this->spots->findOrFail($id);
        return Controller::success('berhasil menampilkan data', $data2);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spots $spots)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spots $spots)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spots $spots)
    {
        //
    }
}
