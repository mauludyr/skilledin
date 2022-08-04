<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomFieldController;
use App\Http\Controllers\Api\CustomParamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomSettingController;
use App\Http\Controllers\Api\CustomTypeController;
use App\Http\Controllers\Api\DurationPerformanceController;
use App\Http\Controllers\Api\EmploymentController;
use App\Http\Controllers\Api\FrequencyReviewController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\HumanResourceController;
use App\Http\Controllers\Api\JobPositionController;
use App\Http\Controllers\Api\LinkedinController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MeasureController;
use App\Http\Controllers\Api\NationalityController;
use App\Http\Controllers\Api\ObjectiveLevelController;
use App\Http\Controllers\Api\OkrPotentialController;
use App\Http\Controllers\Api\OKRController;
use App\Http\Controllers\Api\OrganizationTeamController;
use App\Http\Controllers\Api\PeriodTimeController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserCustomController;
use App\Http\Controllers\Api\ProfileSettingController;
use App\Http\Controllers\Api\ProfileFieldSettingController;
use App\Http\Controllers\Api\VisibilityController;
use App\Http\Controllers\Api\PerformanceController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ConversationController;
use App\Models\ObjectiveLevel;
use App\Models\UserCustomField;

Route::get('test', function() {
    return response()->json(["message" => "test"]);
});

// Route::get('/auth/linkedin/callback', [LinkedinController::class, 'redirect']);

Route::prefix('v1')->middleware('api')->group(function () {

    /** Login */
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    /** Verify User */
    Route::get('/account/verify/{token}', [AuthController::class, 'verify'])->name('user.verify');

    /** Forgot Password */
    Route::post('/reset/password', [AuthController::class, 'forgotPassword'])->name('user.forgot.password');

    /** Reset Password */
    Route::post('/verify/password', [AuthController::class, 'resetPassword'])->name('user.reset.password');

    Route::controller(LinkedinController::class)->group(function() {
        Route::get('/linkedin/callback', 'getCallback');
    });


    Route::middleware('auth:sanctum', 'ability:check-status')->group(function() {

        Route::controller(LinkedinController::class)->group(function() {
            Route::post('/linkedin/information', 'getLinkedInformation');
            Route::post('/linkedin/update/profile', 'updateLinkedinProfile');
        });


        /** Logout */
        Route::controller(AuthController::class)->group(function() {
            Route::post('/logout', 'logout')->name('logout');
        });

        /** Role */
        Route::controller(RoleController::class)->group(function () {
            Route::get('/roles', 'showAll')->name('roles');
            Route::get('/role/{id}', 'showById')->name('role.show');
            Route::post('/role', 'store')->name('role.store');
            Route::put('/role/{id}', 'update')->name('role.update');
            Route::post('sync/role', 'syncStore')->name('sync.role.store');
            Route::put('sync/role/{id}', 'syncUpdate')->name('sync.role.update');
        });

        /** Permission */
        Route::controller(PermissionController::class)->group(function () {
            Route::get('/permissions', 'showAll')->name('permissions');
            Route::get('/permission/{id}', 'showById')->name('permission.show');
            Route::post('/permission', 'store')->name('permission.store');
            Route::put('/permission/{id}', 'update')->name('permission.update');
        });

        /** Grade */
        Route::controller(GradeController::class)->group(function () {
            Route::get('/grades', 'showAll')->name('grades');
            Route::get('/grade/{id}', 'showById')->name('grade.show');
            Route::get('/grade/slug/{slug}', 'showBySlug')->name('grade.show.slug');
            Route::post('/grade', 'store')->name('grade.store');
            Route::put('/grade/{id}', 'update')->name('grade.update');
            Route::delete('/grade/{id}', 'destroy')->name('grade.delete');
        });

        /** Nationality */
        Route::controller(NationalityController::class)->group(function () {
            Route::get('/nationalities', 'showAll')->name('nationalities');
            Route::get('/nationality/{id}', 'showById')->name('nationality.show');
            Route::get('/nationality/code/{code}', 'showByCode')->name('nationality.show.code');
            Route::post('/nationality', 'store')->name('nationality.store');
            Route::put('/nationality/{id}', 'update')->name('nationality.update');
            Route::delete('/nationality/{id}', 'destroy')->name('nationality.delete');
        });

        /** Location */
        Route::controller(LocationController::class)->group(function () {
            Route::get('/locations', 'showAll')->name('locations');
            Route::get('/location/{id}', 'showById')->name('location.show');
            Route::get('/location/code/{code}', 'showByCode')->name('location.show.code');
            Route::post('/location', 'store')->name('location.store');
            Route::put('/location/{id}', 'update')->name('location.update');
            Route::delete('/location/{id}', 'destroy')->name('location.delete');
        });

        /** Job Position */
        Route::controller(JobPositionController::class)->group(function () {
            Route::get('/job_positions', 'showAll')->name('grjob_positionsades');
            Route::get('/job_position/{id}', 'showById')->name('job_position.show');
            Route::get('/job_position/slug/{slug}', 'showBySlug')->name('job_position.show.slug');
            Route::post('/job_position', 'store')->name('job_position.store');
            Route::put('/job_position/{id}', 'update')->name('job_position.update');
            Route::delete('/job_position/{id}', 'destroy')->name('job_position.delete');
        });

        /** Custom Type (Custom Field Type) */
        Route::controller(CustomTypeController::class)->group(function () {
            Route::get('/custom_types', 'showAll')->name('custom_types');
            Route::get('/custom_type/{id}', 'showById')->name('custom_type.show');
            Route::post('/custom_type', 'store')->name('custom_type.store');
            Route::put('/custom_type/{id}', 'update')->name('custom_type.update');
            Route::delete('/custom_type/{id}', 'destroy')->name('custom_type.delete');
        });

        /** Custom Setting (Custom Field Setting) */
        Route::controller(CustomSettingController::class)->group(function () {
            Route::get('/custom_settings', 'showAll')->name('custom_settings');
            Route::get('/custom_setting/{id}', 'showById')->name('custom_setting.show');
            Route::post('/custom_setting', 'store')->name('custom_setting.store');
            Route::put('/custom_setting/{id}', 'update')->name('custom_setting.update');
            Route::delete('/custom_setting/{id}', 'destroy')->name('custom_setting.delete');
        });

        /** Custom Param (Custom Field Params) */
        Route::controller(CustomParamController::class)->group(function () {
            Route::get('/custom_params', 'showAll')->name('custom_params');
            Route::get('/custom_param/{id}', 'showById')->name('custom_param.show');
            Route::get('/custom_param/slug/{slug}', 'showBySlug')->name('custom_param.show.slug');
            Route::post('/custom_param', 'store')->name('custom_param.store');
            Route::put('/custom_param/{id}', 'update')->name('custom_param.update');
            Route::delete('/custom_param/{id}', 'destroy')->name('custom_param.delete');
        });

        /** Custom Field */
        Route::controller(CustomFieldController::class)->group(function() {
            Route::get('/custom_fields', 'showAll')->name('custom_fields');
            Route::get('/custom_field/show/{id}', 'showById')->name('custom_param.show');
            Route::get('/custom_field/list_dropdown/{id}', 'ListDropdown')->name('custom_param.list_dropdown');
            Route::post('/custom_field/store', 'store')->name('custom_field.store');
            Route::put('/custom_field/update', 'update')->name('custom_field.update');
            Route::delete('/custom_field/delete/{id}', 'destroy')->name('custom_field.delete');
        });


        /** User Custom Field */
        Route::controller(UserCustomController::class)->group(function() {
            Route::post("custom/user/store", "storeUserCustomField")->name("custom.user.store");
            Route::post("custom/user/update", "updateUserCustomField")->name("custom.user.update");
        });

        /** Employment */
        Route::controller(EmploymentController::class)->group(function() {
            Route::post('/employment', 'store')->name('employment.store');
            Route::put('/employment/{id}', 'update')->name('employment.update');
        });

        /** Employment */
        Route::controller(ProfileController::class)->group(function() {
            Route::post('/profile', 'store')->name('profile.store');
            Route::post('/profile/update/{id}', 'update')->name('profile.update');
            Route::post('/profile/auth/update', 'updateProfileByAuth')->name('profile.auth.update');
            Route::post('/profile/avatar/update', 'updateUserAvatar')->name('profile.avatar.update');
            Route::get('/profile/schema/show', 'showAllSchema')->name('profile.show.schema');
            Route::get('/particular/show', 'showParticular')->name('show.particular');
            Route::get('/particular/log', 'showParticularLog')->name('show.particular.log');
            Route::post('/request/change', 'change')->name('profile.request.change');
            Route::post('/particular/status/{id}', 'changeStatus')->name('particular.request.status');
            Route::delete('/particular/log/{id}', 'destroyParticularLog')->name('destroy.particular.log');
        });

        /** Measures */
        Route::controller(MeasureController::class)->group(function() {
            Route::get('/measures', 'showAll')->name('measures');
            Route::get('/measure/{id}', 'showById')->name('measure.show');
            Route::get('/measure/slug/{slug}', 'showBySlug')->name('measure.show.slug');
            Route::post('/measure', 'store')->name('measure.store');
            Route::put('/measure/{id}', 'update')->name('measure.update');
        });

        /** Objective Level */
        Route::controller(ObjectiveLevelController::class)->group(function() {
            Route::get('/objective_levels', 'showAll')->name('objective_levels');
            Route::get('/objective_level/{id}', 'showById')->name('objective_level.show');
            Route::get('/objective_level/slug/{slug}', 'showBySlug')->name('objective_level.show.slug');
            Route::post('/objective_level', 'store')->name('objective_level.store');
            Route::put('/objective_level/{id}', 'update')->name('objective_level.update');
        });

        /** Objective Level */
        Route::controller(OkrPotentialController::class)->group(function() {
            Route::get('/objective_potentials', 'showAll')->name('objective_potentials');
            Route::get('/objective_potential/{id}', 'showById')->name('objective_potential.show');
            Route::get('/objective_potential/slug/{slug}', 'showBySlug')->name('objective_potential.show.slug');
            Route::post('/objective_potential', 'store')->name('objective_potential.store');
            Route::put('/objective_potential/{id}', 'update')->name('objective_potential.update');
        });

        /** Period Time */
        Route::controller(PeriodTimeController::class)->group(function() {
            Route::get('/period_review/show/all', 'showAllPeriodReview')->name('period_review.show.all');
            Route::get('/period/show/all', 'showAllPeriod')->name('period.show.all');
            Route::post('/period/store', 'storePeriod')->name('period.store');
            Route::put('/period/update/{id}', 'updatePeriod')->name('period.update');
            Route::delete('/period/delete/{id}', 'deletePeriod')->name('period.delete');
        });

        /** Duration & Performance of Time Period */
        Route::controller(DurationPerformanceController::class)->group(function() {
            Route::get('/duration_performance/period/show/all', 'showAllDurationPerformance')->name('dp.period.show.all');
            Route::get('/duration_performance/period/find/{id}', 'showDurationPerformanceById')->name('dp.period.find');
            Route::post('/duration_performance/period/store', 'storeDurationPerformance')->name('dp.period.store');
            Route::put('/duration_performance/period/update/{id}', 'updateDurationPerformance')->name('dp.period.update');
            Route::delete('/duration_performance/period/delete/{id}', 'deleteDurationPerformance')->name('dp.period.delete');
        });


        /** User */
        Route::controller(UserController::class)->group(function() {
            Route::get('/users', 'showAllUser')->name('user.show.all');
            Route::get('/user', 'showUser')->name('user.show_auth');
            Route::get('/user/admin', 'showUserAdmin')->name('user.show.all');
            Route::get('/user/{id}', 'showById')->name('user.show');
            Route::post('/user/store', 'storeUser')->name('user.store');
            Route::post('/user/change/role', 'storeUserRole')->name('user.change.role');

            Route::post('import/file/employee', 'importFileEmployee')->name('import.file.employee');
            Route::post('import/file/employee/save', 'importFileEmployeeSave')->name('save.import.file.employee');
            Route::get('/download/employee/file/template/{name}', 'downloadEmployeeTemplate')->name('download.employee.file');
        });


        /** Organization Teams */
        Route::controller(OrganizationTeamController::class)->group(function() {
            Route::get('organization_team/show/all', 'showAllTeams')->name('organization_team.show.all');
            Route::get('/organization_team/find/{id}', 'findTeamById')->name("organization_team.find");
            Route::post('/organization_team/store', 'storeTeam')->name("organization_team.store");
            Route::put('/organization_team/update/{id}', 'updateTeam')->name("organization_team.update");
            Route::delete('/organization_team/delete/{id}', 'deleteTeam')->name('organization_team.delete');
        });


        /** Objectives and Key Result */
        Route::controller(OKRController::class)->group(function() {

            //Objective
            Route::get('/parent/objectives', 'showAllParentObjective')->name('parent.objectives');
            Route::get('/objective/count/group', 'getGroupObjective')->name('objective.count.group');
            Route::post('/assign/objective/{id}/to/parent', 'assignObjectToParent')->name('assign.objective.parent');
            Route::get('/objectives/all', 'showAllObjective')->name('objectives');
            Route::get('/objective/show/{id}', 'showObjective')->name('objective.show');
            Route::post('/objective/store', 'storeObjective')->name('objective.store');
            Route::put('/objective/update/{id}', 'updateObjective')->name('objective.update');
            Route::delete('/objective/delete/{id}', 'deleteObjective')->name('objective.delete');


            //Key Result
            Route::get('key_result/auth/show', 'showAllKeyResultByAuth')->name('key_result.auth.show');
            Route::get('/key_result/show/{id}', 'showKeyResultById')->name('key_result.show');
            Route::post('/key_result/store', 'storeKeyResult')->name('key_result.store');
            Route::put('/key_result/update/{id}', 'updateKeyResult')->name('key_result.update');

            //OKR
            Route::get('/okr/show/by/objective/{id}', 'showAllOKRByObjectiveId')->name('okr.show.by.objective');
            Route::post('/okr/checkin/auth', 'checkInOKRByAuthUser')->name('okr.checkin.auth');
            Route::get('/okr/show/all/user/tracking', 'showAllKeyResultUserTracking')->name('okr.show.keys.tracking');


            Route::get('/okr/show/progression', 'showObjectiveKeyResultProgression')->name('okr.show.progression');


            Route::get('/okr/show/all/history/by/objective', 'showAllHistoryKeyResultByObjective')->name('okr.show.history.objective');

            Route::put('/okr/level/update', 'updateOkrLevel')->name('okr.level.update');

        });

        /** Profile Setting */
        Route::controller(ProfileSettingController::class)->group(function() {
            Route::get('/profile_setting', 'showAll')->name('profile_setting');
            Route::get('/profile_setting/{id}', 'showById')->name("profile_setting.show");
            Route::post('/profile_setting', 'store')->name("profile_setting.store");
            Route::put('/profile_setting/{id}', 'update')->name("profile_setting.update");
            Route::delete('/profile_setting/{id}', 'destroy')->name('profile_setting.delete');
        });

        /** Profile Field Setting */
        Route::controller(ProfileFieldSettingController::class)->group(function() {
            Route::get('/profile_field_setting/all', 'showAll')->name('profile_field_setting');
            Route::get('/profile_field_setting/show/{id}', 'showById')->name("profile_field_setting.show");
            Route::post('/profile_field_setting/upgrade', 'upgrade')->name('profile_field_setting.upgrade');
            Route::post('/profile_field_setting/store', 'store')->name("profile_field_setting.store");
            Route::put('/profile_field_setting/update/{id}', 'update')->name('profile_field_setting.update');
            Route::delete('/profile_field_setting/delete/{id}', 'destroy')->name('profile_field_setting.delete');
        });

        /** Visibility */
        Route::controller(VisibilityController::class)->group(function() {
            Route::get('/visibility/show/all', 'showAllVisibilities')->name('visibility.show.all');
        });


        /** Human Resources */
        Route::controller(HumanResourceController::class)->group(function() {
            Route::get('/human-resource/show/all', 'showAllHumanResourceTeams')->name('human_resource.show.all');

            Route::post('/human-resource/store', 'storeHumanResource')->name('human_resource.store');
            Route::put('/human-resource/update/{id}', 'updateHumanResource')->name('human_resource.update');
        });


        /** Frequency Review Date */
        Route::controller(FrequencyReviewController::class)->group(function() {
            Route::get('/frequency/show/all', 'showAllFrequency')->name('frequency.show.all');
            Route::get('/review_date/show', 'showReviewDate')->name('review_date.show');
            Route::post('/frequency_review_date/upgrade', 'upgradeReview')->name('frequency_review_date.upgrade');
        });

        /** Performance */
        Route::controller(PerformanceController::class)->group(function() {
            Route::get('/performance', 'showAll')->name('performance');
            Route::put('/performance/update', 'updatePerformance')->name("performance.update");
            
            Route::put('/reviewer_settings/', 'updateSetting')->name("reviewer_settings.update");

            Route::get('/feedback_question/{id}', 'showById')->name("feedback_question.show");
            Route::post('/feedback_question', 'storeFeedbackQuestion')->name("feedback_question.store");
            Route::put('/feedback_question/update/{id}', 'updateFeedback')->name('feedback_question.update');
            Route::delete('/feedback_question/{id}', 'destroyFeedbackQuestion')->name('feedback_question.delete');
        });

        /** Task */
        Route::controller(TaskController::class)->group(function() {
            Route::get('/task', 'showAll')->name('task');
            Route::get('/task/starred', 'showTaskStarred')->name('task.starred');
            Route::get('/task/status', 'showStatus')->name('task.status');
            Route::get('/task/label', 'showLabel')->name('task.label');
            Route::get('/task/{id}', 'showById')->name("task.show");
            Route::post('/task', 'store')->name("task.store");
            Route::post('/task/status', 'storeStatus')->name("task.label.store");
            Route::post('/task/label', 'storeLabel')->name("task.label.store");
            Route::put('/task/{id}', 'update')->name("task.update");
            Route::put('/task/{type}/{id}', 'updateAction')->name("task.action.update");
            Route::delete('/task/{id}', 'destroy')->name('task.delete');
        });

        /** Conversation */
        Route::controller(ConversationController::class)->group(function() {
            Route::get('/conversation/detail/{id}', 'showById')->name("conversation.show");
            Route::get('/conversation/step', 'getStep')->name('conversation.step');
            Route::get('/conversation/ongoing', 'ongoing')->name('conversation.going');
            Route::get('/conversation/past', 'past')->name('conversation.past');
            Route::post('/conversation', 'store')->name("conversation.store");
            Route::put('/conversation/{id}', 'update')->name("conversation.update");
            Route::post('/conversation/add', 'add')->name("conversation.add");
            Route::post('/conversation/comment/add', 'addComment')->name("conversation.comment.add");
            Route::post('/conversation/step/add', 'addStep')->name("conversation.step.add");
            Route::post('/conversation/okr/add', 'addOkr')->name("conversation.okr.add");
            Route::delete('/conversation/{id}', 'destroyConversation')->name('conversation.delete');
            Route::delete('/conversation/task/{id}', 'destroyConversationTask')->name('conversation.task.delete');
        });
    });
});
