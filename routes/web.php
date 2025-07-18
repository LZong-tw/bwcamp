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
Route::mediaLibrary();
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

//Route::get("/testExport/{camp_id}", function ($camp_id) {
//    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TestExport($camp_id), 'test.xlsx');
//});

Route::group(["prefix" => "camp/{batch_id}"], function () {
    Route::get("/", "CampController@campIndex");
    Route::match(["get", "post"], "/registration", "CampController@campRegistration")->name("registration");
    Route::match(["get", "post"], "/registration_mockup", "CampController@campRegistrationMockUp");
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
    Route::post("/toggleAttendBackend", [CampController::class, "toggleAttendBackend"])->name("toggleAttendBackend");
    Route::post("/modifyTraffic", [CampController::class, "modifyTraffic"])->name("modifyTraffic");
    Route::post("/downloadPaymentForm", "CampController@downloadPaymentForm")->name("downloadPaymentForm");
    Route::post("/downloadCheckInNotification", "CampController@downloadCheckInNotification")->name("downloadCheckInNotification");
    Route::post("/downloadCheckInQRcode", "CampController@downloadCheckInQRcode")->name("downloadCheckInQRcode");
    Route::post("/submit", "CampController@campRegistrationFormSubmitted")->name("formSubmit");
    Route::post("/copy", "CampController@campRegistrationFormCopy")->name("formCopy");
    Route::get("/downloads", "CampController@showDownloads")->name("showDownloads");
    Route::get("/camp_total", [CampController::class, "getCampTotalRegisteredNumber"]);
});

Route::get("/backend", "BackendController@masterIndex")->name("backendIndex");
Route::get("/jobs/{camp_id?}", [AdminController::class, "showJobs"])->name("jobs");
Route::get("/failedJobsClear", [AdminController::class, "failedJobsClear"])->name("failedJobsClear");
Route::middleware(["admin"])->group(function () {
    Route::get('switchToUser/{id}', [BackendController::class, "switchToUser"]);
    Route::get('switch-back', [BackendController::class, "switchUserBack"]);
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
    Route::get("/selectCamp", [CheckInController::class, "selectCamp"])->name("selectCamp");
    Route::get("/", [CheckInController::class, "index"]);
    Route::get("/query", [CheckInController::class, "query"])->name("checkInPage");
    Route::post("/checkin", [CheckInController::class, "checkIn"]);
    Route::post("/mass-checkin", [CheckInController::class, "massCheckIn"])->name("massCheckIn");
    Route::post("/un-checkin", [CheckInController::class, "uncheckIn"]);
    Route::post("/by_QR", [CheckInController::class, "by_QR"]);
    Route::get("/realtimeStat", [CheckInController::class, "realtimeStat"]);
    Route::get("/detailedStat", [CheckInController::class, "detailedStatOptimized"]);
});

Route::group(["prefix" => "backend/campManage"], function(){
    // 營隊基本管理
    Route::group(['prefix' => 'list'], function () {
        Route::get("/", [AdminController::class, "campManagement"])->name("campManagement");
        Route::get("/add", [AdminController::class, "showAddCamp"])->name("showAddCamp");
        Route::post("/add", [AdminController::class, "addCamp"])->name("addCamp");
        Route::get("/modify/{camp_id}", [AdminController::class, "showModifyCamp"])->name("showModifyCamp");
        Route::post("/modify/{camp_id}", [AdminController::class, "modifyCamp"])->name("modifyCamp");
    });

    // 梯次管理
    Route::group(['prefix' => 'batchList'], function () {
        Route::get("/{camp_id}", [AdminController::class, "showBatch"])->name("showBatch");
        Route::get("/{camp_id}/add", [AdminController::class, "showAddBatch"])->name("showAddBatch");
        Route::post("/{camp_id}/add", [AdminController::class, "addBatches"])->name("addBatch");
        Route::post("/{camp_id}/cop", [AdminController::class, "copyBatch"])->name("copyBatch");
        Route::get("/{camp_id}/{batch_id}/modify", [AdminController::class, "showModifyBatch"])->name("showModifyBatch");
        Route::post("/{camp_id}/{batch_id}/modify", [AdminController::class, "modifyBatch"])->name("modifyBatches");
        Route::post("/remove", [AdminController::class, "removeBatch"])->name("removeBatch");
    });

    // 組織管理
    Route::group(['prefix' => 'orgList'], function () {
        Route::get("/{camp_id}", [AdminController::class, "showOrgs"])->name("showOrgs");
        Route::post("/{camp_id}/add", [AdminController::class, "addOrgs"])->name("addOrgs");
        Route::post("/{camp_id}/copy", [AdminController::class, "copyOrgs"])->name("copyOrgs");
        Route::get("/{camp_id}/{org_id}/add", [AdminController::class, "showAddOrgs"])->name("showAddOrgs");
        Route::get("/{camp_id}/{org_id}/duplicate", [AdminController::class, "duplicateOrg"])->name("duplicateOrg");
        Route::get("/{camp_id}/{org_id}/modify", [AdminController::class, "showModifyOrg"])->name("showModifyOrg");
        Route::post("/{camp_id}/{org_id}/modify", [AdminController::class, "modifyOrg"])->name("modifyOrg");
        Route::post("/remove", [AdminController::class, "removeOrg"])->name("removeOrg");
    });
});

Route::group(["prefix" => "backend/{camp_id}", ], function () {
    Route::group(["prefix" => "IOI"], function () {
        Route::get("/learner", [BackendController::class, "showLearners"])->name("showLearners");
        Route::post("/learner", [BackendController::class, "showLearners"])->name("showLearnersPOST");
        Route::get("/volunteer", [BackendController::class, "showVolunteers"])->name("showVolunteers");
        Route::post("/volunteer", [BackendController::class, "showVolunteers"])->name("showVolunteersPOST");
        Route::get('/export/', [BackendController::class, 'export'])->name('export');
        Route::get("/volunteer/userConnection", [BackendController::class, "connectVolunteerToUser"])->name("userConnection");
        Route::post("/volunteer/userConnection", [BackendController::class, "connectVolunteerToUser"])->name("userConnectionPOST");
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
    Route::get("/avatar/{id}", "BackendController@getAvatar")->name("getAvatar");
    Route::get("/file/media/{file}", "BackendController@getFile")->name("getFile");
    Route::get("/image/media/{path}", "BackendController@getMediaImage")->name("getMediaImage");
    Route::get("/logs", "\Rap2hpoutre\LaravelLogViewer\LogViewerController@index")->middleware("permitted")->name("logs");
    Route::group(['prefix' => 'registration'], function () {
        // 報名與審核
        Route::group(['prefix' => 'admission'], function () {
            Route::get("/", "BackendController@admission")->name("admissionGET");
            Route::post("/", "BackendController@admission")->name("admission");
            Route::get("/batch", "BackendController@batchAdmission")->name("batchAdmissionGET");
            Route::post("/batch", "BackendController@batchAdmission")->name("batchAdmission");
            Route::get("/showCandidate", "BackendController@showCandidate")->name("showCandidateGET");
            Route::post("/showCandidate", "BackendController@showCandidate")->name("showCandidate");
            Route::post("/showBatchCandidate", "BackendController@showBatchCandidate")->name("showBatchCandidate");
        });
        // 列表相關
        Route::get("/", "BackendController@showRegistration")->name("showRegistration");
        Route::get("/showUpload", "BackendController@showRegistrationUpload")->name("showRegistrationUpload");
        Route::get("/list", "BackendController@showRegistrationList")->name("showRegistrationList");
        Route::post("/getList", "BackendController@getRegistrationList")->name("getRegistrationList");
        Route::post("/upload", "BackendController@registrationUpload")->name("registrationUpload");
        // 群組與分組
        Route::get("/groupList", "BackendController@showGroupList")->name("showGroupList");
        Route::get("/sectionList", "BackendController@showSectionList")->name("showSectionList");
        Route::get("/group/{batch_id}/{group}", "BackendController@showGroup")->name("showGroup");
        Route::get("/section/{org_id}", "BackendController@showSection")->name("showSection");
        // 郵件發送
        Route::post("/sendAdmittedMail", "BackendController@sendAdmittedMail")->name("sendAdmittedMail");
        Route::post("/sendNotAdmittedMail", "BackendController@sendNotAdmittedMail")->name("sendNotAdmittedMail");
        Route::post("/sendCheckInMail", "BackendController@sendCheckInMail")->name("sendCheckInMail");
        // 其他功能
        Route::get("/showPaymentForm/{applicant_id}", "BackendController@showPaymentForm")->name("showPaymentForm");
        Route::get("/showNotAdmitted", "BackendController@showNotAdmitted")->name("showNotAdmitted");
        Route::get("/modifyAttend", "BackendController@modifyAttend")->name("modifyAttendGET");
    });

    Route::group(['prefix' => 'inCamp'], function () {
        // 交通相關
        Route::get("/trafficList", "BackendController@showTrafficList")->name("showTrafficList");
        Route::get("/trafficListLoc", "BackendController@showTrafficListLoc")->name("showTrafficListLoc");

        // 學員管理
        Route::get("/queryAttendee", "BackendController@queryAttendee")->name("queryAttendee");
        Route::get("/attendeeInfo", "BackendController@showAttendeeInfo")->name("showAttendeeInfoGET");
        Route::post("/attendeeInfo", "BackendController@showAttendeeInfo")->name("showAttendeeInfo");
        Route::post("/cancelRegistration", "BackendController@cancelRegistration")->name("cancelRegistration");
        Route::post("/revertCancellation", "BackendController@revertCancellation")->name("revertCancellation");

        // 刪除相關
        Route::get("/deleteUserRole", "BackendController@deleteUserRole")->name("deleteUserRole");
        Route::get("/deleteApplicantGroupAndNumber", "BackendController@deleteApplicantGroupAndNumber")->name("deleteApplicantGroupAndNumber");
        Route::get("/deleteApplicantCarer", "BackendController@deleteApplicantCarer")->name("deleteApplicantCarer");

        // 其他功能
        Route::get("/volunteerPhoto", "BackendController@showVolunteerPhoto")->name("showVolunteerPhoto");
    });

    // 批次異動路由
    Route::get("/changeBatchOrRegion", "BackendController@changeBatchOrRegion")->name("changeBatchOrRegionGET");
    Route::post("/changeBatchOrRegion", "BackendController@changeBatchOrRegion")->name("changeBatchOrRegion");
    Route::post("/massChangeBatchOrRegion", "BackendController@massChangeBatchOrRegion")->name("massChangeBatchOrRegion");

    //GSheet
    Route::get("/inCamp/gsFeedback/{applicant_id}/{day?}", "SheetController@showGSFeedback")->name("showGSFeedback");
    Route::get("/gsImport", "SheetController@importGSApplicants")->name("importGSApplicants");
    Route::get("/gsExport", "SheetController@exportGSApplicants")->name("exportGSApplicants");
    Route::post("/addDSLink", "AdminController@addDSLink")->name("addDSLink");
    Route::post("/queryDSLink", "AdminController@queryDSLink")->name("queryDSLink");
    Route::get("/showAddDSLink", "AdminController@showAddDSLink")->name("showAddDSLink");
    Route::post("/modifyDSLink", "AdminController@modifyDSLink")->name("modifyDSLink");

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
    //----- Statistics -----
    Route::get("/statistics/ageRange", [StatController::class, "ageRangeStat"])->name("ageRangeStat");
    Route::get("/statistics/appliedDate", [StatController::class, "appliedDateStat"])->name("appliedDateStat");
    Route::get("/statistics/gender", [StatController::class, "genderStat"])->name("genderStat");
    Route::get("/statistics/county", [StatController::class, "countyStat"])->name("countyStat");
    Route::get("/statistics/birthyear", [StatController::class, "birthyearStat"])->name("birthyearStat");
    Route::get("/statistics/favoredEvent", [StatController::class, "favoredEventStat"])->name("favoredEventStat");
    Route::get("/statistics/way", [StatController::class, "wayStat"])->name("wayStat");
    Route::get("/statistics/batches", [StatController::class, "batchesStat"])->name("batchesStat");
    Route::get("/statistics/schoolOrCourse", [StatController::class, "schoolOrCourseStat"])->name("schoolOrCourseStat");
    Route::get("/statistics/region", [StatController::class, "regionStat"])->name("regionStat");
    Route::get("/statistics/education", [StatController::class, "educationStat"])->name("educationStat");
    Route::get("/statistics/industry", [StatController::class, "industryStat"])->name("industryStat");
    Route::get("/statistics/jobProperty", [StatController::class, "jobPropertyStat"])->name("jobPropertyStat");
    Route::get("/statistics/gsTest", [StatController::class, "gsTest"])->name("gsTest");
    Route::get("/statistics/admission", [StatController::class, "admissionStat"])->name("admissionStat");
    Route::get("/statistics/checkin", [StatController::class, "checkinStat"])->name("checkinStat");
    Route::get("/statistics/bwclubschool", BWClubSchoolStat::class)->name("bwclubschoolStat");
    Route::get("/statistics/bwnschool", BWNSchoolStat::class)->name("bwnschoolStat");
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
    Route::get("/signUpload", "SignBackendController@sign_upload")->name("sign_upload");
    Route::post("/signUpdate", "SignBackendController@sign_update")->name("sign_update");

});

/* GSheet Test
Route::get('/sheet', 'SheetController@Sheet');
Route::get('/sheetadd', 'SheetController@AddSheet');
Route::get('/sheettest', 'SheetController@TestSheet');
*/
