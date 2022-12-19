<?php

namespace App\Http\Controllers\admin;

use App\models\postulation;
use App\models\project;
use App\models\User;
use App\models\profil;
use App\models\contact;
use App\models\meet;
use App\models\vote;

use App\models\comment;
use App\models\senior;
use App\models\notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class adminController extends Controller
{





    /***
     * ctrl + f
     * security
     * projects
     * appliations
     * accounts
     * contact
     * comments
     * seniors
     * pre_registration
     *
     *  */


    /********************security*************** */

    public function verifToken($header)
    {

        $tokenParts = explode(".", $header);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        return $jwtPayload;
    }


    /*****************************************************projects*************************** */
    public function getPreprojects(Request $request)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }

        $Projects = DB::table('projects')->where('pre_projectState', 0)->get();
        return response()->json($Projects, 200);
    }

    public function getProjects(Request $request)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }

        $Projects = DB::table('projects')->where('pre_projectState', 1)->where('archiveState', 0)->get();
        return response()->json($Projects, 200);
    }



    public function approuveProject(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $project = project::find($id);
        $project->update(['projectState' => 1]);
        notification::create([
            'user_id' => $project->user_id,
            'name' => $project->name,
            'content' => 'project approuved'
        ]);
    }

    public function denyProject(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $project = project::find($id);
        $project->update(['projectState' => -1]);
        notification::create([
            'user_id' => $project->user_id,
            'name' => $project->name,
            'content' => 'project denied'
        ]);
    }

    public function approuvePreproject(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $project = project::find($id);
        $project->update(['pre_projectState' => 1]);
        notification::create([
            'user_id' => $project->user_id,
            'name' => $project->name,
            'content' => 'pre-project approuved'
        ]);
    }

    public function denyPreproject(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $project = project::find($id);
        $project->update(['pre_projectState' => -1]);
        notification::create([
            'user_id' => $project->user_id,
            'name' => $project->name,
            'content' => 'pre-project denied'
        ]);
    }

    public function deleteProject(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $project = Project::find($id);
        notification::create([
            'user_id' => $project->user_id,
            'name' => $project->name,
            'content' => 'project deleted'
        ]);
        $project->delete();
        return response()->json(null, 204);
    }

//meet
    function addMeet(Request $request)
    {

        $meet = meet::create([
            'StartTime' => $request->StartTime,
            'EndTime' =>  $request->EndTime,
            'description' =>  $request->description,
            'audience' =>  $request->audience,
            'state_meet' => 0,
            'project_id' => $request->project_id,
            'Subject' => project::where('id', $request->project_id)->first()->name,

        ]);
        $vote = vote::create([
            'StartTime' => $request->StartTime,
            'EndTime' =>  $request->EndTime,
           
        ]);
    }
    function getmeet()
    {
        $meets = DB::table('meets')->get(['id', 'StartTime', 'EndTime', 'Subject']);
        return response()->json($meets, 200);
    }
    function getallmeet()
    {
        $meets = DB::table('meets')->get();
        return response()->json($meets, 200);
    }
    function getMeetById($id) //admin
    {
        $meet = DB::table('meets')->where('id_project',$id)->get(['id', 'StartTime', 'EndTime', 'Subject']);
        return response()->json($meet, 200);
    }
    public function deleteMeetById($id)
    {
        $meet= meet ::find($id);
        $meet->delete();
        return response()->json(null, 204);
    }

    public function updateMeet(Request $request, $id) {

        if($request->StartTime){
            DB::table('meets')->where('id', $id)->update(['StartTime' =>$request->StartTime]);

        }
        if($request->EndTime){
            DB::table('meets')->where('id', $id)->update(['EndTime' =>$request->EndTime]);
        }
        if($request->audience){
            DB::table('meets')->where('id', $id)->update(['audience' =>$request->audience]);
        }
        if($request->Subject){
            DB::table('meets')->where('id', $id)->update(['Subject' =>$request->Subject]);
        }
        if($request->location){
            DB::table('meets')->where('id', $id)->update(['state_meet' =>$request->state_meet]);
        }


        DB::table('meets')->where('id', $id)->update(['project_id' =>$request->project_id]);



    }



    /******************************************appliations******************************** */
    public function getPostulationById(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $Postulation = Postulation::find($id);
        return response()->json($Postulation, 200);
    }

    public function getPostulation(Request $request)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $postulation = DB::table('postulations')->where('viewState', 1)->get();
        return response()->json($postulation, 200);
    }
    public function denyPostulation(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $postulation = postulation::find($id);
        $postulation->update(['appState' => -1]);
        notification::create([
            'user_id' => $postulation->user_id,
            'name' => $postulation->name,
            'content' => 'application denied'
        ]);
    }

    public function deletePostulation(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $Postulation = Postulation::find($id);
        notification::create([
            'user_id' => $Postulation->user_id,
            'name' => $Postulation->name,
            'content' => 'application deleted'
        ]);
        $Postulation->delete();
        return response()->json(null, 204);
    }

    public function adminAppPostlation(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $postulation = postulation::find($id);
        $postulation->update(['appState' => 1]);
        notification::create([
            'user_id' => $postulation->user_id,
            'name' => $postulation->name,
            'content' => 'application approuved '
        ]);
    }


    /***************************************accounts****************************************** */
    public function approuveAccount(Request $request, $id)
    {
        /*
        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 6) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/


        $User = User::find($id);
        $User->update(['accountState' => 1]);
    }

    public function deleteAccount(Request $request, $id)
    {

        /*  $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 6) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/


        $User = User::find($id);
        $User->delete();
        $profil = profil::find($id);
        $profil->delete();
    }

    public function getProfilById(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 6) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $profil = profil::find($id);
        return response()->json($profil::find($id), 200);
    }

    public function getPendingAccounts(Request $request)
    {

        /*  $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 6) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/


        $users = DB::table('users')->where('accountState', 0)->get();
        return response()->json($users, 200);
    }

    public function getApprouvedAccounts(Request $request)
    {

        /*  $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 6) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/


        $users = DB::table('users')->where('accountState', 1)->get();
        return response()->json($users, 200);
    }


    public function denyAccount(Request $request, $id)
    {

        /*   $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 6) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/


        $User = User::find($id);
        $User->update(['accountState' => -1]);
    }
    /**********************************contact********************************************* */
    public function getContact(Request $request)
    {

        /*  $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 6) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/


        return response()->json(Contact::all(), 200);
    }

    public function deleteContact(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id != 6) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $Contact = Contact::find($id);
        $Contact->delete();
        return response()->json(null, 204);
    }


    /***********************************comments********************************************* */

    public function addComment(Request $request)
    {
        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);

        $comment = comment::create([
            'username' => User::where('id', $request->user_id)->first()->name,
            'userimg' => profil::where('user_id', $request->user_id)->first()->img,
            'content' =>  $request->content,
            'project_id' =>  $request->project_id,
            'user_id' => $request->user_id
        ]);

        return response($comment, 201);
    }

    public function getCommentsByProject(Request $request, $id)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);


        $comments = DB::table('comments')->where('project_id', $id)->get();
        return response()->json($comments, 200);
    }


    /***************************************************seniors********************** */
    public function addSenior(Request $request, $idp)
    {

        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);


        $senior = senior::create([
            'project_id' => $idp,
            'user_id' => $request->id,
            'name' => $request->name,
            'img' => profil::where('user_id', $request->id)->first()->img,
            'email' =>  $request->email,
            'projectName' => project::where('id', $idp)->first()->name,
        ]);
    }


    public function getMentors(Request $request, $id)
    {
        $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }


        $finalmen = [];
        $mentors = DB::table('users')->where('role_id', 2)->orwhere('role_id', 3)->orwhere('role_id', 4)->get();
        foreach ($mentors as $m) {
            $senior = DB::table('seniors')->where('user_id', $m->id)->where('project_id', $id)->get();
            if ($senior->isEmpty()) {
                array_push($finalmen, $m);
            }
        }
        return response()->json($finalmen, 200);
    }

    /**********8****************************pre_registration******************************* */

    public function ApppreRegisterACA(Request $request, $id)
    {
        /*   $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/
        //  $profil =DB::table('profils')->where('user_id', $id)->get();
        DB::table('profils')->where('user_id', $id)->update(['djACA' => 1]);
        /*  notification::create([
            'user_id' => $profil->user_id,
            'name' => "Djagora Academy",
            'content' => 'pre-registration approuved'
        ]);*/
    }
    public function ApppreRegisterFAB(Request $request, $id)
    {
        /* $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/
        $profil = DB::table('profils')->where('user_id', $id)->get();
        DB::table('profils')->where('user_id', $id)->update(['djFab' => 1]);

        notification::create([
            'user_id' => $profil->user_id,
            'name' => "Djagora Fablab",
            'content' => 'pre-registration approuved'
        ]);
    }
    public function ApppreRegisterOUT(Request $request, $id)
    {
        /* $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/
        $profil = DB::table('profils')->where('user_id', $id)->get();
        DB::table('profils')->where('user_id', $id)->update(['djOUt' => 1]);

        notification::create([
            'user_id' => $profil->user_id,
            'name' => "Djagora Outliers",
            'content' => 'pre-registration approuved'
        ]);
    }
    public function ApppreRegisterMD(Request $request, $id)
    {
        /*  $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/
        $profil = DB::table('profils')->where('user_id', $id)->get();
        DB::table('profils')->where('user_id', $id)->update(['djMD' => 1]);

        notification::create([
            'user_id' => $profil->user_id,
            'name' => "Djagora Maison Digital",
            'content' => 'pre-registration approuved'
        ]);
    }
    public function ApppreRegisterJU(Request $request, $id)
    {
        /* $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/
        $profil = DB::table('profils')->where('user_id', $id)->get();
        DB::table('profils')->where('user_id', $id)->update(['djJU' => 1]);

        notification::create([
            'user_id' => $profil->user_id,
            'name' => "Djagora Junior",
            'content' => 'pre-registration approuved'
        ]);
    }


    public function PendingPreRegisACA(Request $request)
    {
        /*  $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/
        $accounts = DB::table('profils')->where('djACA', 2)->get();
        return response()->json($accounts, 200);
    }
    public function denyRegisterACA(Request $request, $id)
    {
        /*   $header = $request->header('Authorization');
        $tokenData = self::verifToken($header);
        if ($tokenData->role_id < 5) {
            return response()->json([
                'message' => 'access denied'
            ], 401);
        }*/
        //    $profil =DB::table('profils')->where('user_id', $id)->get();
        DB::table('profils')->where('user_id', $id)->update(['djACA' => -1]);
        /*  notification::create([
            'user_id' => $profil->user_id,
            'name' => "Djagora Academy",
            'content' => 'pre-registration denied'
        ]);*/
    }
    public function getDjaAcounts(Request $request)
    {
        $accounts = DB::table('profils')->where('djACA', 1)->get();
        return response()->json($accounts, 200);
    }
}
