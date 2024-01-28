<?php

namespace App\Http\Controllers;

use App\Models\Spots;
use App\Models\society;
use App\Models\Vaccination;
use App\Models\Consultation;
use App\Models\Medical;
use App\Models\SpotVaccines;
use App\Models\Vaccines;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VaccinationController extends Controller
{

    protected $vaccination, $Spots, $societies, $consultation;
    public function __construct(Vaccination $vaccination, Spots $spots, society $societies, Consultation $consultation)
    {
        $this->vaccination = $vaccination;
        $this->Spots = $spots;
        $this->societies =$societies;
        $this->consultation = $consultation;
    }
    /**
     * Display a listing of the resource.
     */
    public function getAllVaccine(Request $request)
    {
        $society = society::where('login_tokens', $request->query('login_tokens'))->first();
        if (!$society) {
            return Controller::failed('Unauthorized user', 401);
        }

        $first = Vaccination::query()->with('spots.regional', 'vaccines', 'vaccinator')->where([
            'society_id' => $society->id,
            'dose' => 1
        ])->first();

        $second = Vaccination::query()->with('spots.regional', 'vaccines', 'vaccinator')->where([
            'society_id' => $society->id,
            'dose' => 2
        ])->first();

        $first_vaccine = Vaccination::where('dose', 1)->orderBy('id', 'asc')->get();
        $second_vaccine = Vaccination::where('dose', 2)->orderBy('id', 'asc')->get();

        if ($first) {
            $first["vaccination_date"] = $first->date;
            $first['status'] = ($first['vaccines'] && $first['vaccinator']) ? 'done' : 'registered';
            $fqueue = $first_vaccine->search(function ($fv) use ($society) {
                return $fv->society_id == $society->id;
            });
            $first['queue'] = $fqueue + 1;
        }
        
        if($second) {
            $second['vaccination_date'] = $second->date;
            $second['status'] = ($first['vaccines'] && $second['vaccinator'] ? 'done' : 'registered');
            $squeue = $second_vaccine->search(function ($sv) use ($society) {
                return $sv->society_id == $society->id;
            });

            $second['queue'] = $squeue + 1;
        }

        $first = collect($first);
        $second = collect($second);
        $forget = ['id', 'date', "society_id", "spot_id", "vaccine_id", "doctor_id", "officer_id"];

        foreach ($forget as $key => $value) {
            $first->forget($value);
            $second->forget($value);
        }

        return response()->json([
            'Vaccination' => [
                'first' => $first,
                'second' => $second,
            ]
        ]);
    }
       
    

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
    public function storeVaccination(Request $request)
    {
        // $token = $request->query('login_tokens');
        // if($token == null)
        // return Controller::Failed('Pengguna Tidak Sah', $token);
        
        // $societies = $this->societies->where('login_tokens', $token)->first();
        // $consultation  = $this->consultation->where('society_id', $societies->id)->first();
        // $vaccination = $this->vaccination->where('society_id', $societies->id)->get();

        // if($consultation && $consultation->status == "accepted"){
        //     if($vaccination->count() < 2)
        //     {

                

        //         return Controller::success('vaccination sukses');
        //     }else{
        //         return Controller::Failed('sudah vaksinasi 2x');
        //     }
        // }else{
        //     return Controller::Failed('consultasi tidak di terimaa');
        // }


        $society = society::where('login_tokens', $request->query('login_tokens'))->first();
        if (!$society) {
            return Controller::failed('Unauthorized user');
        }

        $validated = Validator::make($request->all(), [
            'spot_id' => 'required',
            'date' => 'required|date'
        ], [
            'date' => 'The date does not match the format Y-m-d.'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Gaoleh Vaksin Akeh Akeh engkok mati',
                'errors' => $validated->errors()
            ], 401);
        }

        $consultation = Consultation::where('society_id', $society->id)->first();
        $total_vaccine = Vaccination::where('society_id', $society->id)->get()->count();

        $doctor_id = Medical::where([
            'spot_id' => $request->spot_id,
            'role' => 'doctor'
        ])->first()->id;
        $officer_id = Medical::where([
            'spot_id' => $request->spot_id,
            'role' => 'officer'
        ])->first()->id;

        $spot = Spots::find($request->spot_id);
        $spot_vaccine = SpotVaccines::where('spot_id', $spot->id)->first();
        $vaccine = Vaccines::where('id', $spot_vaccine->vaccine_id)->first();
        $data = $request->all();

        if ($consultation) {
            if ($consultation->status == 'accepted') {

                if ($total_vaccine >= 2) {
                    return Controller::failed("Society has been 2x vaccinated", 401);
                } else if ($total_vaccine == 1) {

                    $first_vaccination = Vaccination::where('society_id', $society->id)->first();
                    $second_vaccination_date = Carbon::parse($request->date);
                    $date_diff = $second_vaccination_date->diffInDays(Carbon::parse($first_vaccination->date));
                    if ($date_diff >= 30) {
                        $data['dose'] = 2;
                        $data['society_id'] = $society->id;
                        $data['vaccine_id'] = $vaccine->id;
                        $data['doctor_id'] = $doctor_id;
                        $data['officer_id'] = $officer_id;

                        Vaccination::create($data);
                        
                        return Controller::success("Second vaccination registered successful", 200);
                    } else {
                        return Controller::failed('Wait at least +30 days from 1st Vaccination', 401);
                    }
                } else {
                    $data['dose'] = 1;
                    $data['society_id'] = $society->id;
                    $data['vaccine_id'] = $vaccine->id;
                    $data['doctor_id'] = $doctor_id;
                    $data['officer_id'] = $officer_id;

                    Vaccination::create($data);

                    return Controller::success('First vaccination registered successful', 200);
                }
            } else {
                return Controller::failed("Your consultation must be accepted by doctor before", 401);
            }
        }
        return Controller::failed("Your must consultation before", 401);
       
    }

  

    /**
     * Display the specified resource.
     */
    public function showVaccination($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vaccination $vaccination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vaccination $vaccination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccination $vaccination)
    {
        //
    }
}
