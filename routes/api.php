<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// User
Route::get('/users', 'UserController@index');

Route::post('/users', 'UserController@store');

Route::get('/users/{user}', 'UserController@show');

Route::put('/users/{user}', 'UserController@update');

Route::delete('/users/{user}', 'UserController@destroy');

Route::put('/users/{user}/password/update', 'UserController@updatePassword');


// Loanee
Route::get('/loanees', 'LoaneeController@index');

Route::post('/loanees', 'LoaneeController@store');

Route::get('/loanees/stats', 'LoaneeController@stats');

Route::get('/loanees/{loanee}', 'LoaneeController@show');

Route::put('/loanees/{loanee}', 'LoaneeController@update');

Route::delete('/loanees/{loanee}', 'LoaneeController@destroy');

// Route::get('/loanees/{loanee}/stats', 'LoaneeController@loanStats');



// Plan
Route::get('/plans', 'PlanController@index');

Route::post('/plans', 'PlanController@store');

Route::get('/plans/{plan}', 'PlanController@show');

Route::put('/plans/{plan}', 'PlanController@update');

Route::delete('/plans/{plan}', 'PlanController@destroy');


// Rate
Route::get('/rates', 'RateController@index');

Route::post('/rates', 'RateController@store');

Route::get('/rates/{rate}', 'RateController@show');

Route::put('/rates/{rate}', 'RateController@update');

Route::delete('/rates/{rate}', 'RateController@destroy');


// Loan
Route::get('/loans', 'LoanController@index');

Route::get('/loans/stats', 'LoanController@getStats');

Route::post('/loans', 'LoanController@store');

Route::get('/loans/{loan}', 'LoanController@show');

Route::put('/loans/{loan}', 'LoanController@update');

Route::delete('/loans/{loan}', 'LoanController@destroy');

Route::post('/calculate/loan', 'CalculateController@calculate');



// Loan review
Route::put('/loans/{loan}/approve', 'LoanController@approveLoan');

Route::put('/loans/{loan}/deny', 'LoanController@denyLoan');


// Schedule
Route::get('/schedules', 'ScheduleController@index');

Route::get('schedules/{schedule}', 'ScheduleController@show');
