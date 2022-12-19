<?php

namespace App\Http\Controllers\mentor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\models\user;
use App\models\profil;
use Auth;
use App\models\role;
use App\models\gender;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class registerMentorController extends Controller
{


  //----------register

  public function register(request $request){

   $validator = Validator::make($request->all(), [
      'name'=>'required',
      'email'=>'required|email|unique:users',
      'gender'=>'required',
      'role'=>'required',
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
      'role_id'=>role::where('name',  $request->role)->first()->id,
      'gender_id' => gender::where('name', $request->gender)->first()->id,

      'password' => bcrypt ($request->password)
   ]);
     $profil=profil::create([
        'name'=>$user->name,
        'user_id'=> $user->id,
        'name'=>$user->name,
        'gender_id'=> $user->gender_id,
        'role_id'=>$user->role_id,
        'email' => $user->email,
        'img'=>gender::where('id', $user->gender_id)->first()->img,
      ]);







   }




}

