<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\society;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    protected $consultation, $societies;
    public function __construct(Consultation $consultation, society $societies)
    {
        $this->consultation = $consultation;
        $this->societies = $societies;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store( Request $request)
    {
       $token = $request->query('login_tokens');
       if($token == null)
       return Controller::Failed('Unauthorized user', $token);

       $societies = $this->societies->where('login_tokens', $token)->first();
       
       
       $request->validate([
           'disease_history'=> 'required',
           'current_symptoms'=> 'required',
       ]);

       $consultationData = Consultation::where('society_id', $societies->id)->first();
       if ($consultationData) {
        return Controller::Failed('society aldready'); 
       }

       $create = collect($request->only($this->consultation->getFillable()))
       ->put('society_id', $societies->id)
       ->toArray();
      $new = $this->consultation->create($create);
      return Controller::success('Request consultation sent successful', $new);
    }




    /**
     * Display the specified resource.
     */
    public function showConsultation(Request $request)
    {
        $token = $request->query('login_tokens');
       if($token == null)
       return Controller::Failed('Unauthorized user', $token);

       $societies = $this->societies->where('login_tokens', $token)->first();

       $consultationData = Consultation::where('society_id', $societies->id)->first();
       return Controller::success('Berhasil Menampilkan Data', $consultationData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
