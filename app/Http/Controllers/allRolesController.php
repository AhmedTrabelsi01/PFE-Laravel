<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request ;
use App\models\project;
use App\models\postulation;
use App\models\User;
use App\models\gender;
use App\models\profil;

use App\models\role;
use App\models\notification;


use Carbon\Carbon;



use Illuminate\Support\Facades\DB;

class allRolesController extends Controller
{

   /******************************archive*********************** */
    public function getArchive()
    {
        $Projects = DB::table('projects')->where('projectState', 1)->where('archiveState', 1)->get();
        return response()->json($Projects, 200);
    }
    public function updateArchiveState($id)
    {
        $projet = project::find($id);
        $projet->update(['archiveState' => 1]);
    }


/**********************************projects*********************** */
    public function getProject( )
    {
      
        $currDate=Carbon::now()->toDateString();
        $currDate=strtotime($currDate);
        $currDate=date('Y-m-d', $currDate);
        $proj=DB::table('projects')->where('projectState', 1)->where('archiveState', 0)->get();
        foreach($proj as $p){
            $d=strtotime($p->estimated_date);
            $d=date('Y-m-d', $d);

            if($d < $currDate){
               
                $projet = project::find($p->id);
                $projet->update(['archiveState' => 1]);
            }
        }
        $Projects=DB::table('projects')->where('projectState', 1)->where('archiveState', 0)->get();
        return response()->json($Projects, 200);
    }


    public function getProjectById($id)
    {
        $Project = Project::find($id);
        return response()->json($Project, 200);
    }

/***********************************appliations************************ */

    public function getPostulationByProject($id)
    {
        $postulation = DB::table('postulations')->where('project_id', $id)->get();
        return response()->json($postulation, 200);
    }

    public function getSinglePostulationByProject(Request $request)
    {
        $postulation = DB::table('postulations')->where('project_id', $request->project_id)->where('user_id', $request->user_id)->first();
        return response()->json($postulation, 200);
    }
    public function getAppPosByProject($id)
    {
        $apps = DB::table('postulations')->where('project_id', $id)->where('appState', 1)->get();
        return response()->json($apps, 200);
    }

    public function getCountApps($id)
    {
        $apps = DB::table('postulations')->where('project_id', $id)->get();
        $count=count($apps);
        return response()->json($count, 200);
    }



/***************************************notifications********************* */

    public function getNotificationsByUser($id)
    {

        $notifs = DB::table('notifications')->where('user_id', $id)->get();
        
        return response()->json($notifs, 200);
    }

    public function setNotifsState($id){
        $notifs = DB::table('notifications')->where('user_id', $id)->where('state',0)->get();
        foreach($notifs as $n){
            $noti = notification::find($n->id);
            $noti->update(['state' => 1]);
        }
    }

    public function getNewNotifs($id)
    {
        $notifs = DB::table('notifications')->where('user_id', $id)->where('state',0)->get();
        return response()->json($notifs, 200);
    }

    public function delNotifications($id){
        $notif = notification::find($id);
        $notif->delete();
        return response()->json(null, 204);
    }

/*********************************comments****************************** */

    public function getCommentsByProject($id)
    {
        $comments = DB::table('comments')->where('project_id', $id)->get();
        return response()->json($comments, 200);
    }


  
/*
    public function getExecutiveTeam($id)
    {
        $team = DB::table('teams')->get();
        return response()->json($team, 200);
    }*/


  

/*************************************seniors*********************** */

    public function getSeniorByProject($id)
    {
        $senior = DB::table('seniors')->where('project_id', $id)->get();
        return response()->json($senior, 200);
    }

/*********************************pre_registration***************************** */

    public function preRegisterACA (Request $request, $id){
        DB::table('profils')->where('user_id', $id)->update(['djACA' =>2]);          
    }
    public function preRegisterFAB (Request $request, $id){
        DB::table('profils')->where('user_id', $id)->update(['djFab' =>2]);       
    }
    public function preRegisterOUT (Request $request, $id){
        DB::table('profils')->where('user_id', $id)->update(['djOUt' =>2]);       
    }
    public function preRegisterMD (Request $request, $id){
        DB::table('profils')->where('user_id', $id)->update(['djMD' =>2]);       
    }
    public function preRegisterJU (Request $request, $id){
        DB::table('profils')->where('user_id', $id)->update(['djJU' =>2]);       
    }



}
