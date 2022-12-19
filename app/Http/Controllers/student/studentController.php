<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\postulation;
use App\models\profil;
use App\models\comment;
use App\models\project;
use App\models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class studentController extends Controller
{


     /***
 * ctrl + f 
 * security
 * applications
 * comments 
 *  */  

/***************************security********************** */

    public function verifToken($header)
    {

        $tokenParts = explode(".", $header);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        return $jwtPayload;
    }



/***************************applications********************** */



    public function addPostulation(Request $request)
    {
/*
        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 1) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/




        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'number' => 'required|min:8',
            'cv' => 'required|File',

        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $Postulation = Postulation::create([
            'name' => $request->name,
            'img' => profil::where('user_id', $request->user_id)->first()->img,
            'email' =>  $request->email,
            'viewState' => 0,
            'appState' => 0,
            'projectName' => project::where('id', $request->project_id)->first()->name,
            'number' =>  $request->number,
            'cv' =>  $request->cv,
            'project_id' => $request->project_id,
            'user_id' => $request->user_id
        ]);


        if ($request->hasFile('cv')) {
            $completeFileName = $request->file('cv')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('cv')->getClientOriginalExtension();
            $compPic = str_replace("", "_", $fileNameOnly) . '_' . rand() . '_' . time() . '.' . $extension;
            $path = $request->file('cv')->storeAs('public/post', $compPic);
            $Postulation->cv = $compPic;
            if ($Postulation->save()) {
            }
        }
    }

    public function updatePostulation(Request $request, $id)
    {

        $Postulation = Postulation::find($id);

       /* $owner_id = $Postulation->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 1 || $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
*/

        DB::table('postulations')->where('id', $Postulation->id)->update(['email' => $request->email]);
        DB::table('postulations')->where('id', $Postulation->id)->update(['number' => $request->number]);

        if ($request->hasFile('cv')) {
            $completeFileName = $request->file('cv')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('cv')->getClientOriginalExtension();
            $compPic = str_replace("", "_", $fileNameOnly) . '_' . rand() . '_' . time() . '.' . $extension;
            $path = $request->file('cv')->storeAs('public/post', $compPic);
            DB::table('postulations')->where('id', $Postulation->id)->update(['cv' => $compPic]);
        }

        return response($Postulation, 200);
    }

    public function deletePostulation(Request $request, $id)
    {

        $Postulation = Postulation::find($id);

        $owner_id = $Postulation->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 1 || $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }

        $Postulation->delete();
        return response()->json(null, 204);
    }


    public function getOwnedPostulation(Request $request, $id)
    {
/*

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 1 || $tokenData->id != $id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
*/

        $postulations = DB::table('postulations')->where('user_id', $id)->get();
        return response()->json($postulations, 200);
    }

    public function getOwnedPostulationByProject(Request $request)
    {
        $postulation = DB::table('postulations')->where('user_id', $request->user_id)
            ->where('project_id', $request->project_id)
            ->get();
        return response()->json($postulation, 200);
    }

    public function getPostulationById(Request $request, $id)
    {
/*
        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
*/

        $Postulation = Postulation::find($id);
        return response()->json($Postulation, 200);
    }





/***************************comments********************** */




    public function addComment(Request $request) {
/*
        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 1 ) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/
        $comment = comment::create([
            'username' => User::where('id', $request->user_id)->first()->name,
            'userimg' => profil::where('user_id', $request->user_id)->first()->img,
            'content' =>  $request->content,
            'project_id' =>  $request->project_id,
            'user_id' => $request->user_id
        ]);

        return response($comment, 201);
    }
}
