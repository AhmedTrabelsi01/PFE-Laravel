<?php

namespace App\Http\Controllers;
use App\models\profil;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class profilController extends Controller
{
    public function verifToken($header){
        
        $tokenParts = explode(".", $header);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        return $jwtPayload;
    }


    public function getProfilById(Request $request,$id) {
        

        $profil =DB::table('profils')->where('user_id', $id)->first();
         return response()->json($profil, 200);
    }

    public function updateProfil(Request $request, $id) {

        $header = $request->header('Authorization');
        $tokenData=self::verifToken($header);
        if($tokenData->id!=$id){
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }

        if($request->birth_date){
            DB::table('profils')->where('user_id', $id)->update(['birth_date' =>$request->birth_date]);
            $currDate=Carbon::now()->toDateString();
             $currDate=strtotime($currDate);
            $currDate=date('Y', $currDate);

            $birth=strtotime($request->birth_date);
             $birth=date('Y', $birth);
             $age=$currDate-$birth;
            DB::table('profils')->where('user_id', $id)->update(['age' => $age]);

        }
        if($request->linkedin){
            DB::table('profils')->where('user_id', $id)->update(['linkedin' =>$request->linkedin]);
        }
        if($request->domain){
            DB::table('profils')->where('user_id', $id)->update(['domain' =>$request->domain]);
        }
        if($request->profession){
            DB::table('profils')->where('user_id', $id)->update(['profession' =>$request->profession]);
        }
        if($request->location){
            DB::table('profils')->where('user_id', $id)->update(['location' =>$request->location]);
        }
       

        DB::table('profils')->where('user_id', $id)->update(['name' =>$request->name]);
        DB::table('profils')->where('user_id', $id) ->update(['email' =>$request->email]);

        
        if ($request->hasFile('img')) {
            $completeFileName=$request->file('img')->getClientOriginalName();
            $fileNameOnly=pathinfo($completeFileName,PATHINFO_FILENAME);
            $extension=$request->file('img')->getClientOriginalExtension();
             $compPic= str_replace("","_",$fileNameOnly).'_'.rand().'_'.time().'.'.$extension;
             $path= $request->file('img')->storeAs('public/post',$compPic);
            DB::table('profils')->where('user_id', $id)->update(['img' =>$compPic]);
        }
    }

}
