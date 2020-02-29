<?php

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

Auth::routes();

/*Route::get('tt/cvs/{filename}', function ($filename)
{
    $path = ('public/cvs/'.$filename);
            //return $path;
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});*/
Route::get('tt/cvs/{filename}', array('middleware' => 'cors', 'uses' =>'CandidateController@get')); //candi cvs
Route::get('tt/logo/{filename}', array('middleware' => 'cors', 'uses' =>'CompanyController@get')); //company logo

Route::get('/', function () {
    return 2;
});

Route::get('/home', 'HomeController@index')->name('home');


// A-route
Route::get("/aroute", function() {
    Artisan::call("cache:clear");
    //php artisan make:middleware Cors

});

// Z-route
Route::get("/zroute", function() {
    Artisan::call("route:cache");
});

Route::get("/mytest", function() {
    return view("welcome");
});


Route::get('/{companyName}/{candiId}/{jobId}/{randomNumber}','Candi_app_status_Controller@showurl'); //show the data in url which sent by email

Route::get('/show_intime_candi', array('middleware' => 'cors', 'uses' =>   'TimeController@show_intime_candi'))->name('show_intime_candi'); //show available times to candi
Route::patch('/applyTimeInterview', array('middleware' => 'cors', 'uses' =>'Candi_app_status_Controller@applyTimeInterview')); //candi apply timeInterview
Route::patch('/update_candi_time', array('middleware' => 'cors', 'uses' =>'TimeController@update_candi_time')); //candi update timeInterview
Route::patch('/cancel_candi_time', array('middleware' => 'cors', 'uses' =>'TimeController@cancel_candi_time')); //candi cancel timeInterview



Route::post('/entermail', array('middleware' => 'cors', 'uses' =>'LoginController@enterEmail')); // Enter E-mail if email exist return 1; else return 0; in 2 cases go to password
Route::post('/login', array('middleware' => 'cors', 'uses' =>'LoginController@login')); // check mail first
Route::post('/ResetPassword', array('middleware' => 'cors', 'uses' =>'LoginController@resetPassword')); // Reset New Password
Route::post('/createPin', array('middleware' => 'cors', 'uses' =>'LoginController@createPin')); // access
Route::get('/user', array('middleware' => 'cors', 'uses' => 'LoginController@getAuthenticatedUser'));

Route::get('/show_genders', array('middleware' => 'cors', 'uses' =>'GenderController@showAll'));
Route::get('/show_locations', array('middleware' => 'cors', 'uses' =>'LocationController@showAll'));
Route::get('/show_universities', array('middleware' => 'cors', 'uses' =>'UniversityController@showAll'));
Route::post('/refresh', array('middleware' => 'cors', 'uses' =>'LoginController@refresh'));



Route::get('/jobtypes', array('middleware' => 'cors', 'uses' => 'JobController@ddJobtypes'));
Route::get('/jobs', array('middleware' => 'cors', 'uses' =>    'JobController@getJobs'));  //get all jobs of company
Route::get('/job-detail', array('middleware' => 'cors', 'uses' =>    'JobController@jobDetails')); // get details about job by companyId and jobId

Route::get('{random}/{company}', array('middleware' => 'cors', 'uses' =>    'CompanyController@detectCompany')); //info about company
Route::get('/{random}/{company}/login', array('middleware' => 'cors', 'uses' =>    'CompanyController@detectCompany')); //info about company
Route::post('/job/store', array('middleware' => 'cors', 'uses' =>  'CandidateController@store'));    // candi apply job/training
Route::get('/showallJobs', array('middleware' => 'cors', 'uses' =>    'JobController@showallJobs'));//get all jobs for admin of company


Route::group(['middleware' => ['token_access']], function() 
{
  //  Route::get('/output/completed', 'ExcelController@completed');
    Route::post('/add Company', array('middleware' => 'cors', 'uses' => 'CompanyController@addCompany'));
    Route::get('/update Company', array('middleware' => 'cors', 'uses' => 'CompanyController@updateCompany'));
    Route::get('/delete Company', array('middleware' => 'cors', 'uses' => 'CompanyController@deleteCompany'));
    Route::get('/show Companies', array('middleware' => 'cors', 'uses' => 'CompanyController@showCompanies'));



    Route::get('/show privlage', array('middleware' => 'cors', 'uses' => 'UserController@showPrivlage'));
    Route::get('/show Profiles', array('middleware' => 'cors', 'uses' => 'UserController@showProfile'));
    Route::post('/addUser', array('middleware' => 'cors', 'uses' => 'UserController@storeuser'));
    Route::get('/updateUser', array('middleware' => 'cors', 'uses' => 'UserController@updateuser'));
    Route::get('/showUser', array('middleware' => 'cors', 'uses' =>'UserController@showuser'));
    Route::get('/deleteUser', array('middleware' => 'cors', 'uses' => 'UserController@deleteuser'));



    Route::get('/status', array('middleware' => 'cors', 'uses' =>'Candi_app_status_Controller@show')); //show all status


    Route::post('/storeJob', array('middleware' => 'cors', 'uses' =>   'JobController@StoreJob')); // create new job
    Route::patch('/update', array('middleware' => 'cors', 'uses' =>    'JobController@editjob')); //update job
    Route::delete('/delete', array('middleware' => 'cors', 'uses' =>    'JobController@deletejob')); //delete job

    Route::get('/filter', array('middleware' => 'cors', 'uses' =>   'FilterController@filter')); //get candies info by date,jobname,jobtype,companyId
    //Route::patch('/candiStatus/update', array('middleware' => 'cors', 'uses' =>   'Candi_status_controller@update'));  //update candi status


    Route::post('/addTime', array('middleware' => 'cors', 'uses' =>   'TimeController@storetime'));  //create new timeInterview to job
    Route::patch('/status/update', array('middleware' => 'cors', 'uses' =>   'Candi_app_status_Controller@update')); //update interview status

    Route::get('/showTime', array('middleware' => 'cors', 'uses' =>   'TimeController@show_intime_admin'))->name('showTime');// show times to admin by (time,date,jobName)

    Route::patch('/update_time/{candicount}', array('middleware' => 'cors', 'uses' =>'TimeController@edit_time')); // update time
    Route::delete('/delete_time/{candicount}', array('middleware' => 'cors', 'uses' =>'TimeController@delete_time')); //delete time

    Route::get('/showCandiTime', array('middleware' => 'cors', 'uses' =>'TimeController@showCandiTime'))->name('showCandiTime'); //show the time that candi apply
    
    
    Route::delete('/deleteCandiCompletely', array('middleware' => 'cors', 'uses' =>    'CandidateController@deleteCompletely')); //delete candi from candi table,candi job table and dropbox 
    Route::delete('/deleteCandiPartially', array('middleware' => 'cors', 'uses' =>    'CandidateController@deletePartially')); //delete candi from candi job table only
    Route::post('/shiftData', array('middleware' => 'cors', 'uses' =>    'CandidateController@shiftData')); //shift candi data
    
    
    Route::get('/showchannels', array('middleware' => 'cors', 'uses' =>    'candi_evaluation_Controller@showchannels'));
    Route::get('/showlevels', array('middleware' => 'cors', 'uses' =>    'candi_evaluation_Controller@showlevels'));
    Route::post('/addevaluation', array('middleware' => 'cors', 'uses' =>    'candi_evaluation_Controller@addevaluation'));
    Route::get('/showevaluation', array('middleware' => 'cors', 'uses' =>    'candi_evaluation_Controller@showevaluation'));
    

});

