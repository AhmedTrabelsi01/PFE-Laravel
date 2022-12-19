<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\student\registerStudentController;
use App\Http\Controllers\student\studentController;
use App\Http\Controllers\mentor\registerMentorController;
use App\Http\Controllers\mentor\mentorController;
use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\profilController;
use App\Http\Controllers\allRolesController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*****************************************authentification*************************** */


Route::post('/login',[loginController::class,'login']);
Route::post('/logout',[loginController::class,'logout']);
Route::post('/checkLogin',[loginController::class,'checkLogin']);
Route::post('/refresh',[loginController::class,'refresh']);


/*****************************************mentor*************************** */


Route::post('/register_men',[registerMentorController::class,'register']);


//--------------projects

Route::post('/addproject',[mentorController::class, 'addProject']);
Route::get('/getownedproj/{id}',[mentorController::class, 'getOwnedProjects']);
Route::post('/updateproj/{id}',[mentorController::class, 'updateProject']);
Route::delete('/delproj/{id}',[mentorController::class, 'deleteProject']);
Route::put('endproj/{id}', [mentorController::class, 'endProject']);


//------------applications

Route::put('uppos/{id}',[mentorController::class, 'mentorAppPos']);//viewState


    //------startup

    Route::post('/addstartup',[mentorController::class, 'addStartup']);
    Route::post('/updatestartup/{id}',[mentorController::class, 'updateStartup']);
    Route::delete('/deletestartup/{id}',[mentorController::class, 'deleteStartup']);
    Route::get('/getownerstar/{id}',[mentorController::class, 'getOwnedStartup']);
    Route::get('/getstarbyid/{id}',[mentorController::class, 'getStartupById']);

    //------history

    Route::post('/addhistory',[mentorController::class, 'addHistory']);
    Route::post('/updatehistory/{id}',[mentorController::class, 'updateHistory']);
    Route::delete('/deletehistory/{id}',[mentorController::class, 'deleteHistory']);
    Route::get('/getownedhist/{id}',[mentorController::class, 'getOwnedHistory']);
    Route::get('/gethistbyid/{id}',[mentorController::class, 'getHistoryById']);

//------------meets

Route::get('/getvotes',[mentorController::class, 'getActiveVote']);
Route::post('/upvote/{id}',[mentorController::class, 'upVote']);
Route::post('/downvote/{id}',[mentorController::class, 'downVote']);
Route::post('/getownedvote',[mentorController::class, 'getOwnedVote']);



/*****************************************student*************************** */



Route::post('/register_stud',[registerStudentController::class,'register']);
Route::post('/addpostulation',[studentController::class,'addPostulation']);
Route::post('/updateapp/{id}',[studentController::class,'updatePostulation']);
Route::delete('/delapp/{id}',[studentController::class,'deletepostulation']);
Route::get('/getownedapp/{id}',[studentController::class, 'getOwnedPostulation']);
Route::get('/posbyid/{id}',[studentController::class, 'getPostulationById']);

Route::post('/addcom', [studentController::class, 'addComment']);
Route::post('checkcomm', [studentController::class, 'getOwnedPostulationByProject']); // pending accounts



/*****************************************admin*************************** */



//------accounts

Route::put('verifacc/{id}', [adminController::class, 'approuveAccount']);
Route::delete('delacc/{id}', [adminController::class, 'deleteAccount']);
Route::get('penacc', [adminController::class, 'getPendingAccounts']); // pending accounts
Route::get('appacc', [adminController::class, 'getApprouvedAccounts']); // approuved accounts
Route::put('deny/{id}', [adminController::class, 'denyAccount']);


//------projects

Route::get('preprojects', [adminController::class, 'getPreprojects']);
Route::get('preojects', [adminController::class, 'getProjects']); // approuved projects
Route::put('appproj/{id}', [adminController::class, 'approuveProject']);
Route::put('denyproj/{id}', [adminController::class, 'denyProject']);
Route::put('apppreproj/{id}', [adminController::class, 'approuvePreproject']);
Route::put('denypreproj/{id}', [adminController::class, 'denyPreproject']);
Route::delete('delproj/{id}', [adminController::class, 'deleteProject']);
Route::post('addMeet', [adminController::class, 'addMeet']);

Route::post('getmeet', [adminController::class, 'getmeet']);
Route::post('getallmeet', [adminController::class, 'getallmeet']);

Route::post('getMeetById/{id}', [adminController::class, 'getMeetById']);

Route::delete('deleteMeetById/{id}', [adminController::class,'deleteMeetById']);

Route::post('updateMeet/{id}', [adminController::class, 'updateMeet']);



//------application

Route::get('postulation/{id}', [adminController::class, 'getPostulationById']);
Route::get('postulations', [adminController::class, 'getPostulation']);
Route::put('/apppos/{id}',[adminController::class, 'adminAppPostlation']);
Route::put('/denypos',[adminController::class, 'denyPostulation']);
Route::delete('/delpos/{id}',[adminController::class, 'deletePostulation']);


//------contact

Route::get('contacts', [adminController::class, 'getContact']);
Route::delete('/delcon/{id}',[adminController::class, 'deleteContact']);


//-------comments

Route::get('/com/{id}', [adminController::class, 'getCommentsByProject']);
Route::post('/addComment', [adminController::class, 'addComment']);


//--pre register confirmation

Route::post('apppreregACA/{id}', [adminController::class, 'ApppreRegisterACA']);
Route::post('denypergACA/{id}', [adminController::class, 'denyRegisterACA']);
Route::post('apppreregJU/{id}', [adminController::class, 'ApppreRegisterJU']);
Route::post('apppreregOUT/{id}', [adminController::class, 'ApppreRegisterOUT']);
Route::post('apppreregFAB/{id}', [adminController::class, 'ApppreRegisterFAB']);
Route::post('apppreregMD/{id}', [adminController::class, 'ApppreRegisterMD']);
Route::get('penprereg', [adminController::class, 'PendingPreRegisACA']);
Route::get('getDjaAcounts', [adminController::class, 'getDjaAcounts']);


//----------seniors

Route::get('/getmen/{id}',[adminController::class, 'getMentors']);
Route::post('addsenior/{id}', [adminController::class, 'addSenior']);



/********************************all_roles*************************** */



Route::post('/sendContact', [ContactController::class, 'addContact']);
Route::get('executiveteam', [allRolesController::class, 'getExecutiveTeam']);


//------archive

Route::get('archive', [allRolesController::class, 'getArchive']);
Route::put('uparch/{id}', [allRolesController::class, 'updateArchiveState']);


//-----notifications

Route::get('countnotifs/{id}', [allRolesController::class, 'getNewNotifs']);
Route::get('notifs/{id}', [allRolesController::class, 'getNotificationsByUser']);
Route::get('setnotifs/{id}', [allRolesController::class, 'setNotifsState']);
Route::delete('/delnot/{id}',[allRolesController::class, 'delNotifications']);


//-------applications

Route::get('sinpostulation', [allRolesController::class,'getSinglePostulationByProject']);
Route::get('postulations/{id}', [allRolesController::class,'getPostulationByProject']);
Route::get('appposbyproj/{id}', [allRolesController::class, 'getAppPosByProject']);
Route::get('getcountapp/{id}', [allRolesController::class, 'getCountApps']);


//-------projects

Route::get('projects', [allRolesController::class, 'getProject']);
Route::get('project/{id}', [allRolesController::class, 'getProjectById']);


//------pre register

Route::post('preregACA/{id}', [allRolesController::class, 'preRegisterACA']);
Route::post('preregJU/{id}', [allRolesController::class, 'preRegisterJU']);
Route::post('preregOUT/{id}', [allRolesController::class, 'preRegisterOUT']);
Route::post('preregFAB/{id}', [allRolesController::class, 'preRegisterFAB']);
Route::post('preregMD/{id}', [allRolesController::class, 'preRegisterMD']);


//---------- profil

Route::get('profil/{id}', [profilController::class, 'getProfilById']);
Route::post('updateprofil/{id}', [profilController::class, 'updateProfil']);


//--------comments

Route::get('/comments/{id}', [allRolesController::class, 'getCommentsByProject']);


//-----------seniors

Route::get('senbyproj/{id}', [allRolesController::class, 'getSeniorByProject']);



/************************************************************************************************************ */
