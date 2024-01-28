<?php

namespace App\Http\Controllers;

use App\Models\society;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    protected $society;
    public function __construct(society $society)
    {
        $this->society = $society;
    }

    public function login(Request $request){
        $request->validate([
               'id_card_number' => 'required',
               'password' => 'required'
           ]);
           

            //    if(!$validateData){
            //        return Controller::Failed('Gagal Login');
            //    }
           $society = society::where('id_card_number', $request->id_card_number)->where('password', $request->password)->with(['regional'])->first();
           
           if(!$society) return Controller::Failed('Gagal Login');
   
           $token = md5($request->id_card_number);
         $society->update(['login_tokens' => $token]);  
   
         return Controller::success('Berhasil Login', $society);
       }
   
   
       public function logout(Request $request){
           $token =$request->query('login_tokens');
           if(!$token) return Controller::failed('token kosong');
   
       $user =society::where('login_tokens', $token)->first();
       $user->update(['login_tokens' => null]);
           return Controller::success('Berhasil Logout');
       }
   }
