<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\SignController as ArrayedSignController;
use App\Http\Controllers\LaratrustController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RolesController;
use App\Http\Controllers\Auth\LaratrustPermissionsController;
use App\Http\Controllers\Auth\RolesAssignmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", Index::class);
Route::resource("sign_page", SignController::class);
Route::post("sign/search", [ArrayedSignController::class, 'search'])->name("sign_page.search");

/***********************Auth routes******************************************/
// Auth::routes();
// Authentication Routes...
Route::get("login", "Auth\LoginController@showLoginForm")->name("login");
Route::post("login", "Auth\LoginController@login");
Route::post("logout", "Auth\LoginController@logout")->name("logout");

// Registration Routes...
Route::get("register", "Auth\RegisterController@showRegistrationForm")->name("register");
Route::post("register", "Auth\RegisterController@register");

// Password Reset Routes...
Route::get("password/reset", "Auth\ForgotPasswordController@showLinkRequestForm")->name("password.request");
Route::post("password/email", "Auth\ForgotPasswordController@sendResetLinkEmail")->name("password.email");
Route::get("password/reset/{token}", "Auth\ResetPasswordController@showResetForm")->name("password.reset");
Route::post("password/reset", "Auth\ResetPasswordController@reset")->name("password.update");

Route::get("profile/", "Auth\UserController@showDashboard")->name("profile");
Route::post("profile/update", "Auth\UserController@updateProfile")->name("profile.update");
/****************************************************************************/

Route::get("/home", "HomeController@index")->name("home");

Route::group(["prefix" => "camp/{batch_id}"], function () {
    Route::get("/", "CampController@campIndex");
    Route::match(["get", "post"], "/registration", "CampController@campRegistration")->name("registration");
    Route::post("/restoreCancellation", [CampController::class, "restoreCancellation"])->name("restoreCancellation");
    Route::get("/query", "CampController@campQueryRegistrationDataPage")->name("query");
    Route::get("/payment", [CampController::class, "showCampPayment"])->name("payment");
    Route::post("/queryview", "CampController@campViewRegistrationData")->name("queryview");
    Route::post("/queryupdate", "CampController@campViewRegistrationData")->name("queryupdate");
    Route::post("/querysn", "CampController@campGetApplicantSN")->name("querysn");
    Route::get("/queryadmit", "CampController@campViewAdmission")->name("queryadmitGET");
    Route::post("/queryadmit", [CampController::class, "campQueryAdmission"])->name("queryadmit");
    Route::post("/querycancel", [CampController::class, "campConfirmCancel"])->name("querycancel");
    Route::post("/cancel", [CampController::class, "campCancellation"])->name("cancel");
    Route::get("/showadmit", [CampController::class, "campQueryAdmission"])->name("showadmit");
    Route::post("/toggleAttend", [CampController::class, "toggleAttend"])->name("toggleAttend");
    Route::post("/downloadPaymentForm", "CampController@downloadPaymentForm")->name("downloadPaymentForm");
    Route::post("/downloadCheckInNotification", "CampController@downloadCheckInNotification")->name("downloadCheckInNotification");
    Route::post("/downloadCheckInQRcode", "CampController@downloadCheckInQRcode")->name("downloadCheckInQRcode");
    Route::post("/submit", "CampController@campRegistrationFormSubmitted")->name("formSubmit");
    Route::get("/downloads", "CampController@showDownloads")->name("showDownloads");
    Route::get("/camp_total", [CampController::class, "getCampTotalRegisteredNumber"]);
});

Route::get("/backend", "BackendController@masterIndex")->name("backendIndex");
Route::get("/jobs/{camp_id?}", [AdminController::class, "showJobs"])->name("jobs");
Route::get("/failedJobsClear", [AdminController::class, "failedJobsClear"])->name("failedJobsClear");
Route::middleware(["admin"])->group(function () {
    Route::get("/userlist/{camp_id?}", [AdminController::class, "userlist"])->name("userlist");
    Route::get("/user/userAddRole/{user_id}", [AdminController::class, "userAddRole"])->name("userAddRole");
    Route::post("/user/removeRole", [AdminController::class, "removeRole"])->name("removeRole");
    Route::post("/user/addRole", [AdminController::class, "addRole"])->name("addRole");
    Route::get("/role/edit/{role_id}", [AdminController::class, "editRole"])->name("editRoleGET");
    Route::post("/role/edit/{role_id}", [AdminController::class, "editRole"])->name("editRole");
    Route::get("/rolelist/{camp_id?}", [AdminController::class, "rolelist"])->name("rolelist");
    Route::post("/listrole/removeRole", [AdminController::class, "listRemoveRole"])->name("listRemoveRole");
    Route::get("/listrole/addRole/{camp_id?}", [AdminController::class, "listAddRole"])->name("listAddRoleGET");
    Route::post("/listrole/addRole/{camp_id?}", [AdminController::class, "listAddRole"])->name("listAddRole");
});

Route::group(["prefix" => "checkin", ], function () {
    Route::get("/", [CheckInController::class, "index"]);
    Route::get("/query", [CheckInController::class, "query"]);
    Route::post("/checkin", [CheckInController::class, "checkIn"]);
    Route::post("/un-checkin", [CheckInController::class, "uncheckIn"]);
    Route::post("/by_QR", [CheckInController::class, "by_QR"]);
    Route::get("/realtimeStat", [CheckInController::class, "realtimeStat"]);
    Route::get("/detailedStat", [CheckInController::class, "detailedStatOptimized"]);
});

Route::group(["prefix" => "backend/campManage"], function(){
    Route::get("/list", [AdminController::class, "campManagement"])->name("campManagement");
    //Camps
    Route::post("/list/add", [AdminController::class, "addCamp"])->name("addCamp");
    Route::get("/list/add", [AdminController::class, "showAddCamp"])->name("showAddCamp");
    Route::post("/list/modify/{camp_id}", [AdminController::class, "modifyCamp"])->name("modifyCamp");
    Route::get("/list/modify/{camp_id}", [AdminController::class, "showModifyCamp"])->name("showModifyCamp");
    //Batches
    Route::post("/batchList/{camp_id}/add", [AdminController::class, "addBatches"])->name("addBatch");
    Route::get("/batchList/{camp_id}/add", [AdminController::class, "showAddBatch"])->name("showAddBatch");
    Route::post("/batchList/{camp_id}/cop", [AdminController::class, "copyBatch"])->name("copyBatch");
    Route::get("/batchList/{camp_id}", [AdminController::class, "showBatch"])->name("showBatch");
    Route::post("/batchList/{camp_id}/{batch_id}/modify", [AdminController::class, "modifyBatch"])->name("modifyBatches");
    Route::get("/batchList/{camp_id}/{batch_id}/modify", [AdminController::class, "showModifyBatch"])->name("showModifyBatch");
    //Camp Organization
    Route::post("/orgList/{camp_id}/add", [AdminController::class, "addOrgs"])->name("addOrgs");
    Route::post("/orgList/{camp_id}/copy", [AdminController::class, "copyOrgs"])->name("copyOrgs");
    Route::get("/orgList/{camp_id}/{org_id}/add", [AdminController::class, "showAddOrgs"])->name("showAddOrgs");
    Route::get("/orgList/{camp_id}", [AdminController::class, "showOrgs"])->name("showOrgs");
    Route::post("/orgList/{camp_id}/{org_id}/modify", [AdminController::class, "modifyOrg"])->name("modifyOrg");
    Route::get("/orgList/{camp_id}/{org_id}/modify", [AdminController::class, "showModifyOrg"])->name("showModifyOrg");
    Route::post("/orgList/remove", [AdminController::class, "removeOrg"])->name("removeOrg");
});

Route::group(["prefix" => "backend/{camp_id}", ], function () {
    Route::group(["prefix" => "IOI"], function () {
        Route::get("/learner", [BackendController::class, "showLearners"])->name("showLearners");
        Route::get("/volunteer", [BackendController::class, "showVolunteers"])->name("showVolunteers");
        Route::get("/carer", [BackendController::class, "showCarers"])->name("showCarers");
    });
    Route::resource('/permissions', LaratrustPermissionsController::class, ['as' => 'laratrustCustom'])
        ->only(['index', 'create', 'store', 'edit', 'update']);
    Route::resource('/roles', RolesController::class, ['as' => 'laratrustCustom']);
    Route::resource('/roles-assignment', RolesAssignmentController::class, ['as' => 'laratrustCustom'])
        ->only(['index', 'edit', 'update']);
    Route::group(["prefix" => "{batch_id}"], function () {
    });
    Route::get("/", "BackendController@campIndex")->name("campIndex");
    Route::get("/logs", "\Rap2hpoutre\LaravelLogViewer\LogViewerController@index")->middleware("permitted")->name("logs");
    Route::get("/registration/admission", "BackendController@admission")->name("admissionGET");
    Route::get("/registration/showPaymentForm/{applicant_id}", "BackendController@showPaymentForm")->name("showPaymentForm");
    Route::get("/registration/batchAdmission", "BackendController@batchAdmission")->name("batchAdmissionGET");
    Route::post("/registration/showCandidate", "BackendController@showCandidate")->name("showCandidate");
    Route::post("/registration/showBatchCandidate", "BackendController@showBatchCandidate")->name("showBatchCandidate");
    Route::post("/registration/admission", "BackendController@admission")->name("admission");
    Route::post("/registration/batchAdmission", "BackendController@batchAdmission")->name("batchAdmission");
    Route::get("/registration", "BackendController@showRegistration")->name("showRegistration");
    Route::get("/registration/list", "BackendController@showRegistrationList")->name("showRegistrationList");
    Route::get("/changeBatchOrRegion", "BackendController@changeBatchOrRegion")->name("changeBatchOrRegionGET");
    Route::post("/changeBatchOrRegion", "BackendController@changeBatchOrRegion")->name("changeBatchOrRegion");
    Route::post("/registration/getList", "BackendController@getRegistrationList")->name("getRegistrationList");
    Route::post("/registration/sendAdmittedMail", "BackendController@sendAdmittedMail")->name("sendAdmittedMail");
    Route::post("/registration/sendNotAdmittedMail", "BackendController@sendNotAdmittedMail")->name("sendNotAdmittedMail");
    Route::post("/registration/sendCheckInMail", "BackendController@sendCheckInMail")->name("sendCheckInMail");
    Route::get("/registration/groupList", "BackendController@showGroupList")->name("showGroupList");
    Route::get("/registration/showNotAdmitted", "BackendController@showNotAdmitted")->name("showNotAdmitted");
    Route::get("/registration/group/{batch_id}/{group}", "BackendController@showGroup")->name("showGroup");
    Route::get("/inCamp/trafficList", "BackendController@showTrafficList")->name("showTrafficList");
    Route::get("/inCamp/attendeePhoto", "BackendController@showAttendeePhoto")->name("showAttendeePhoto");
    Route::get("/inCamp/attendeeList", "BackendController@showAttendeeList")->name("showAttendeeList");
    Route::get("/inCamp/attendeeAssign", "BackendController@showAttendeeAssign")->name("showAttendeeAssign");

    Route::get("/inCamp/queryAttendee", "BackendController@queryAttendee")->name("queryAttendee");
    Route::get("/inCamp/attendeeInfo", "BackendController@showAttendeeInfo")->name("showAttendeeInfoGET");
    Route::post("/inCamp/attendeeInfo", "BackendController@showAttendeeInfo")->name("showAttendeeInfo");
    //Remark
    Route::post("/remark/edit", [BackendController::class, "editRemark"])->name("editRemark");
    //Contact Log
    Route::post("/contactLog/add", [BackendController::class, "addContactLog"])->name("addContactLog");
    Route::get("/contactLog/{applicant_id}/add", [BackendController::class, "showAddContactLogs"])->name("showAddContactLogs");
    Route::get("/contactLog/{applicant_id}", [BackendController::class, "showContactLogs"])->name("showContactLogs");
    Route::post("/contactLog/modify", [BackendController::class, "modifyContactLog"])->name("modifyContactLog");
    Route::get("/contactLog/{log_id}/modify", [BackendController::class, "showModifyContactLog"])->name("showModifyContactLog");
    Route::post("/contactLog/remove", [BackendController::class, "removeContactLog"])->name("removeContactLog");

    Route::get("/registration/groupAttendList", "BackendController@showGroupAttendList")->name("showGroupAttendList");
    Route::get("/statistics/appliedDate", [StatController::class, "appliedDateStat"])->name("appliedDateStat");
    Route::get("/statistics/gender", [StatController::class, "genderStat"])->name("genderStat");
    Route::get("/statistics/county", [StatController::class, "countyStat"])->name("countyStat");
    Route::get("/statistics/birthyear", [StatController::class, "birthyearStat"])->name("birthyearStat");
    Route::get("/statistics/way", [StatController::class, "wayStat"])->name("wayStat");
    Route::get("/statistics/batches", [StatController::class, "batchesStat"])->name("batchesStat");
    Route::get("/statistics/schoolOrCourse", [StatController::class, "schoolOrCourseStat"])->name("schoolOrCourseStat");
    Route::get("/statistics/educationStat", [StatController::class, "educationStat"])->name("educationStat");
    Route::get("/statistics/admission", [StatController::class, "admissionStat"])->name("admissionStat");
    Route::get("/statistics/checkin", [StatController::class, "checkinStat"])->name("checkinStat");
    Route::get("/statistics/bwclubschool", BWClubSchoolStat::class)->name("bwclubschoolStat");
    Route::get("/accounting", "BackendController@showAccountingPage")->name("accounting");
    Route::get("/accounting/modify", "BackendController@modifyAccounting")->name("modifyAccountingGET");
    Route::post("/accounting/modify", "BackendController@modifyAccounting")->name("modifyAccounting");
    Route::get("/customMail", "BackendController@customMail")->name("customMail");
    Route::get("/writeMail", "BackendController@writeCustomMail")->name("writeMail");
    Route::post("/customMail/send", "BackendController@sendCustomMail")->name("sendMail");
    Route::get("/customMail/selectMailTarget", "BackendController@selectMailTarget")->name("selectMailTarget");
    Route::get("/permissionScopes", [PermissionController::class, "showPermissionScope"])->name("permissionScopes");
//    Route::get("/roles", [PermissionController::class, "showRoles"])->name("roles");
    Route::resource("sign", SignBackendController::class)
            ->names([
                "index" => "sign_back",
                "store" => "sign_set_back",
                "destroy" => "sign_delete_back"
            ]);
});
