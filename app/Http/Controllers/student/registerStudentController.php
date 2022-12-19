<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\User;
use App\models\role;
use App\models\gender;

use App\models\profil;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Validator;


class registerStudentController extends Controller
{
    //----------register  
  
   public function register(request $request){

      $validator = Validator::make($request->all(), [
         'name'=>'required',
         'email'=>'required|email|unique:users',
         'gender'=>'required',
         'password'=>'required|min:8',
         'confirmation'=>'required|same:password'
      ]);
   
      if ($validator->fails()) {
        return $validator->errors();
     }

    $user=User::where('email',$request['email'])->first();
    if ($user) {
       $response['message']='Email exists';
    }

    $user =User::create([


       'name' => $request->name,
       'email' =>  $request->email,
       'accountState'=>0,
       'role_id'=>role::where('name', 'student')->first()->id,
       'gender_id' => gender::where('name', $request->gender)->first()->id,
       'password' => bcrypt ($request->password)
    ]);
      $profil=profil::create([
         'name'=>$user->name,
         'user_id'=> $user->id,
         'djACA'=> 1,
         'name'=>$user->name,
         'role_id'=>$user->role_id,
         'email' => $user->email,
         'gender_id'=> $user->gender_id,
         'img'=>gender::where('id', $user->gender_id)->first()->img,
       ]);  

   



    
  
  }

 


}
