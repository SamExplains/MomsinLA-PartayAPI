<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\StandardSignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('partybemine')->group(function () {
    Route::post('/signup', [StandardSignupController::class, 'store']);
    Route::post('/quicklogin', [StandardSignupController::class, 'quickLogin']);
    Route::post('/login', [StandardSignupController::class, 'login']);
    // GET check if a sub account with the matching sub is saved
    Route::post('/google', [StandardSignupController::class, 'checkIfGoogleAccountIsStored']);
    Route::post('/create', [EventController::class, 'create']);
    Route::get('/events/{filter}', [EventController::class, 'index']);
    // GET events created by the user
    Route::get('/events/creator/{uid}', [EventController::class, 'getCreatorEvents']);
    Route::get('/events/search/{query}', [EventController::class, 'search']);
    // PATCH event registered field
    Route::patch('/events/{eid}', [EventController::class, 'updateRegistered']);
    // PATCH event checklist
    Route::patch('/checklist', [EventController::class, 'updateChtecklist']);

    // Find private event by shareable key and regster a user
    Route::post("/events/private/{eventId}", [EventController::class, 'addPrivateEvent']);

    Route::delete('/registration', [RegistrationController::class, 'destroy']);
    // INSERT registration record
    Route::post('/registration', [RegistrationController::class, 'store']);
    // GET registered events list
    Route::get('/registration/{uid}', [RegistrationController::class, 'index']);
    // GET check if user is registered for event
    Route::get('/registration/user/{uid}/{eid}', [RegistrationController::class, 'checkIfUserIsRegistered']);
    // PATCH registration record
    Route::patch('/registration/{registration}', [RegistrationController::class, 'update']);

    // Check a user in
    Route::patch("/registration/user/checkin", [RegistrationController::class, 'checkUserIn']);
    // Get Registration Check-In count
    Route::get('/registration/checkin/{eid}', [RegistrationController::class, 'returnCheckinAmount']);
    // PATCH user profile details
    Route::patch('/user/details', [StandardSignupController::class, 'updateProfileDetails']);

    // PATCH update event
    Route::patch('/events/edit/update', [EventController::class, 'update']);

    // Send password reset email
    Route::post('/user/password-reset-request', [StandardSignupController::class, 'sendForgotPasswordEmail']);

    // Validate code password reset
    Route::post('/user/password-reset', [StandardSignupController::class, 'updateUserPassword']);

});