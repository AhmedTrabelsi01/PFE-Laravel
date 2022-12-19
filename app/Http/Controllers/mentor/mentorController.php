<?php

namespace App\Http\Controllers\mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\project;
use App\models\startup;
use App\models\User;
use App\models\vote;
use App\models\voteLog;

use Carbon\Carbon;

use App\models\history;
use App\models\notification;

use Illuminate\Support\Facades\DB;

use App\models\postulation;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Validator;

class mentorController extends Controller
{

   /***
 * ctrl + f 
 * security
 * project
 * startup
 * history
 * applications
 * 
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

/***************************project********************** */


    public function getOwnedProjects(Request $request, $id)
    {

    
        $projects = DB::table('projects')->where('user_id', $id)->get();
         /* $owner_id = $projects->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/


        return response()->json($projects, 200);
    }

    public function addProject(Request $request)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id == 1  ) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'estimated_date' => 'required',
            'img' => 'required|File',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $project = project::create([
            'name' => $request->name,
            'estimated_date' =>  $request->estimated_date,
            'description' =>  $request->description,
            'pre_projectState' => 0,
            'projectState' => 0,
            'finishState' => 0,
            'archiveState' => 0,
            'img' =>  $request->img,
            'user_id' => $request->user_id,
            'owner' => User::where('id', $request->user_id)->first()->name,
        ]);

        if ($request->hasFile('img')) {
            $completeFileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('img')->getClientOriginalExtension();
            $compPic = str_replace("", "_", $fileNameOnly) . '_' . rand() . '_' . time() . '.' . $extension;
            $path = $request->file('img')->storeAs('public/post', $compPic);
            $project->img = $compPic;
            if ($project->save()) {
            }
        }
    }

    public function updateProject($id, Request $request)
    {
        $project = project::find($id);

        $owner_id = $project->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ( $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }



        DB::table('projects')->where('id', $project->id)->update(['name' => $request->name]);
        DB::table('projects')->where('id', $project->id)->update(['estimated_date' => $request->estimated_date]);
        DB::table('projects')->where('id', $project->id)->update(['description' => $request->description]);

        if ($request->hasFile('img')) {
            $completeFileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('img')->getClientOriginalExtension();
            $compPic = str_replace("", "_", $fileNameOnly) . '_' . rand() . '_' . time() . '.' . $extension;
            $path = $request->file('img')->storeAs('public/post', $compPic);
            DB::table('projects')->where('id', $project->id)->update(['img' => $compPic]);
        }
    }

    public function endProject(Request $request, $id)
    {

        $project = project::find($id);

        $owner_id = $project->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ( $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
        $project->update(['finishState' => 1]);
    }

    public function deleteProject(Request $request, $id)
    {

        $project = project::find($id);

        $owner_id = $project->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ( $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
        $project->delete();
        return response()->json(null, 204);
    }



/***************************startup********************** */


    public function addStartup(Request $request)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id == 1  ) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'img' => 'required|File',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $startup = startup::create([
            'name' => $request->name,
            'description' =>  $request->description,
            'img' =>  $request->img,
            'user_id' => $request->user_id
        ]);
        if ($request->hasFile('img')) {
            $completeFileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('img')->getClientOriginalExtension();
            $compPic = str_replace("", "_", $fileNameOnly) . '_' . rand() . '_' . time() . '.' . $extension;
            $path = $request->file('img')->storeAs('public/post', $compPic);
            $startup->img = $compPic;
            if ($startup->save()) {
            }
        }
    }

    public function updateStartup($id, Request $request)
    {
        $startup = startup::find($id);
        $owner_id = $startup->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ( $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
        

        DB::table('startups')->where('id', $id)->update(['name' => $request->name]);
        DB::table('startups')->where('id', $id)->update(['description' => $request->description]);

        if ($request->hasFile('img')) {
            $completeFileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('img')->getClientOriginalExtension();
            $compPic = str_replace("", "_", $fileNameOnly) . '_' . rand() . '_' . time() . '.' . $extension;
            $path = $request->file('img')->storeAs('public/post', $compPic);
            DB::table('startups')->where('id', $id)->update(['img' => $compPic]);
        }
    }

    public function deleteStartup(Request $request, $id)
    {

        $startup = startup::find($id);

        $owner_id = $startup->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ( $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
        $startup->delete();
        return response()->json(null, 204);
    }

    public function getOwnedStartup(Request $request, $id)
    {
        $startups = DB::table('startups')->where('user_id', $id)->get();
        return response()->json($startups, 200);
    }


    public function getStartupById(Request $request, $id)
    {
        $startup = startup::find($id);
        return response()->json($startup, 200);
    }


/***************************history********************** */



    public function addHistory(Request $request)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id == 1  ) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'img' => 'required|File',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $history = history::create([
            'name' => $request->name,
            'description' =>  $request->description,
            'img' =>  $request->img,
            'user_id' => $request->user_id
        ]);
        if ($request->hasFile('img')) {
            $completeFileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('img')->getClientOriginalExtension();
            $compPic = str_replace("", "_", $fileNameOnly) . '_' . rand() . '_' . time() . '.' . $extension;
            $path = $request->file('img')->storeAs('public/post', $compPic);
            $history->img = $compPic;
            if ($history->save()) {
            }
        }
    }

    public function updateHistory(Request $request, $id)
    {

        $history = history::find($id);
        $owner_id = $history->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ( $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
        DB::table('histories')->where('id', $id)->update(['name' => $request->name]);
        DB::table('histories')->where('id', $id)->update(['description' => $request->description]);

        if ($request->hasFile('img')) {
            $completeFileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $request->file('img')->getClientOriginalExtension();
            $compPic = str_replace("", "_", $fileNameOnly) . '_' . rand() . '_' . time() . '.' . $extension;
            $path = $request->file('img')->storeAs('public/post', $compPic);
            DB::table('histories')->where('id', $id)->update(['img' => $compPic]);
        }
    }

    public function deleteHistory(Request $request, $id)
    {

        $history = history::find($id);

        $owner_id = $history->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ( $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }

        $history->delete();
        return response()->json(null, 204);
    }

    public function getOwnedHistory(Request $request, $id)
    {
        $history = DB::table('histories')->where('user_id', $id)->get();
        return response()->json($history, 200);
    }


    public function getHistoryById(Request $request, $id)
    {
        $history = history::find($id);
        return response()->json($history, 200);
    }

/***************************applications********************** */


    public function mentorAppPos(Request $request, $id)
    {
        $postulation = postulation::find($id);
        $project=project::find($postulation->project_id);
        $owner_id = $project->user_id;

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ( $tokenData->id != $owner_id) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }
        $postulation->update(['viewState' => 1]);
        notification::create([
            'user_id' => $postulation->user_id,
            'name' => $postulation->name,
            'content' => 'application displayed '
        ]);
    }



    /***************************meet********************** */
    public function getActiveVote(Request $request)
    {
        $activeVotes=[];
        $today=Carbon::now()->toDateString();
        $today=strtotime($today);
        $today=date('Y-m-d', $today);

        $votes=DB::table('votes')->get();

        foreach($votes as $v){
            $d=strtotime($v->StartTime);
            $d=date('Y-m-d', $d);

            if($d == $today){
                array_push($activeVotes, $v);
            }
        }

        foreach($activeVotes as $vote){

        $currDate=Carbon::now();
        $currDate = Carbon::createFromFormat('Y-m-d H:i:s', $currDate);
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $vote->StartTime);
        $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $vote->EndTime);

        $result = $currDate->gt($startTime);
        $result2 =$currDate->lt($endTime);

        if($result==true && $result2==true){
            $v = vote::find($vote->id); 
            $v->update(['state' => 1]);
        }

        if($result2==false){
             $v = vote::find($vote->id);
             $v->update(['state' => 0]); 
        }
    }
       $votes=DB::table('votes')->where('state',1)->first();
       return response()->json($votes, 200);
   
    }

    public function upVote(Request $request,$id){
        $v = vote::find($id); 
        $v->increment('upVotes');
        voteLog::create([
            'user_id' => $request->user_id,
            'vote_id' => $id,
            'voteType' => 1
        ]);
    }

    public function downVote(Request $request,$id){
        $v = vote::find($id); 
        $v->increment('downVotes');
        voteLog::create([
            'user_id' => $request->user_id,
            'vote_id' => $id,
            'voteType' => -1
        ]);

    }

    public function getOwnedVote(Request $request){
        $votes=DB::table('vote_logs')->where('user_id',$request->user_id)->where('vote_id',$request->vote_id)->get();
        return response()->json($votes, 200);

    }



}
