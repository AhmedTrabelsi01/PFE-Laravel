<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class loginController extends Controller
{
   
//------------login

public function login(Request $request) {
   

    $fields = $request->only('email','password');
    $user = User::where('email', $fields['email'])->first();

    if(!$user || !Hash::check($fields['password'], $user->password)) {
        return response([
            'message' => 'Please verify yours creds !'
        ]);
    }
    if($user->accountState==0){
        return response([
            'message'=> 'Your account is not verified yet !'
        ]);
    }

   $token=auth()->claims([
        'id' => $user->id,
        'name'=> $user->name,
        'email' => $user->email,
        'role_id' => $user->role_id 
    ])->attempt($fields);

    $response = [
        'token' => $token,
        'message' => 'success'
        ];

        $user = JWTAuth::user();

        return response()->json(compact('token'));
   
 }
 
 
    //--------------------logout
 
 
    public function logout(Request $request) {
       auth()->logout();

        return response()->json([
            'message' => 'logged out'
        ], 200);
    }

    public function checkLogin(Request $request) {
       if( Auth()->check()){
            return response()->json([
            'status' => 'logged in',
        ], 200);
        }else{
            return response()->json([
                'status' => 'logged out',
            ], 200);
        }  

    }
    
    public function refresh(){
        return auth()->refresh();
    }
   





}
