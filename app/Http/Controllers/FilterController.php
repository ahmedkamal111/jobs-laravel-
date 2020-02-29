<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Candidate;
use App\jobType;
use App\location;
use DateTime;
use App\Candi_app_status;

class FilterController extends Controller
{
   public function filter(Request $request)
  {
      $jobType=$request->input('jobType');
      $jobName=$request->input('jobName');
      $CID=$request->input('CID');
      $startDate=$request->input('startDate');
      $endDate=$request->input('endDate');

    if(strlen($endDate)==1)
    {
      $now=date('Y-m-d H:i:s');
      $diff = abs(strtotime($now)-($endDate*30*60*60*24+60));
      $startDate= date('Y-m-d H:i:s', $diff);
      $endDate= $now;
      //return $startDate;
    }
    else if($endDate==-1)
    {
      $endDate=strtotime("+3 months", strtotime($startDate));
      $endDate= date('Y-m-d', $endDate);
    }
    $symbol="";
    if($jobType!=5)
    {
        $symbol="!=";
    }
    else if($jobType==5){
        $symbol="=";
    }
    $candi = Candidate::whereBetween('ds',[$startDate, $endDate])
                      ->leftJoin('locations','candi.loc','locations.id')
                      ->leftJoin('univ','candi.university','univ.id')
                      ->JOIN('candi_jobs','candi.id','candi_jobs.Candi_id')
                      ->JOIN('jobs','candi_jobs.job_id','jobs.id')
                      ->leftJoin('gender','candi.gender','gender.id')
                      ->where('candi.CID', $CID)
                      //->where('jobs.job_Type',$symbol,'5')
                      ->where('jobs.en','1')
                      ->where('jobs.Name',$jobName)
                      ->whereBetween('ds',[$startDate,$endDate])
                      ->limit(300)
                      ->select('candi.id','candi.name','candi.email','gender.name As Gender','candi.dob','candi.mobile','candi.linkedin','univ.Name As university','candi_jobs.salary','locations.name  As location','jobs.name As nameOfJob','candi.cv_file','candi_jobs.id As candi_job_id','candi_jobs.st','candi.ds As date','candi.cv_link','candi_jobs.job_id')
                      ->selectRaw('TIMESTAMPDIFF( YEAR, dob,  CURDATE()) AS age')
                      ->selectRaw('get_ext(candi.cv_file) AS ext')
                      ->orderBy('candi.id')->get();
    //$count=$candi->count();
    //$candi->count=$count;
    return  $candi;
  }
}
