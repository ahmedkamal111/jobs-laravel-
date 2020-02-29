<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Candidate;
use App\jobType;
use DB;
class JobController extends Controller
{
    public function getJobs(Request $request) 
    {
        // $candi = Candidate::find([2,3]);
        //$jobs = $candi->getJob(explode(",",$candi->job_id));
        // foreach()
        // return json_encode($candi);
        $cid = $request->input("cid");
        $jobs = Job::select('CID','id','Name','Job_Type','d1')->where([
            ["cid", "=", $cid],
            ['en', '=', '1']
            ])->orderByRaw('d1 - id DESC')->get();
        return json_encode($jobs);
    }
//=====================================================================
   public function showallJobs(Request $request) 
    {
        //SELECT `CID`, `id`, `Name`, `Job_Type`, `en`, `Respo`, `Skills`, `d1` FROM `jobs` WHERE 1
        $cid = $request->input("cid");
        $jobs = Job::select('CID','id','Name','Job_Type','Respo','Skills','d1','en')->where([
            ["cid", "=", $cid]
            ])->get();
        return json_encode($jobs);
    } 
//=========================================================================
    public function jobDetails(Request $request) 
    {
         
        $id=$request->input("jobId");
        $cid=$request->input("cid");
        $job = Job::where([
            ['CID', '=', $cid],
            ['id', '=', $id],
            ['en', '=', '1']
            ])->get();
        if(count($job) == 1)
        {
            $job = $job[0];
            $jobType = jobType::find($job->Job_Type);
            $job->Job_Type = $jobType->name;
        }
        
        return json_encode($job);
    }
//==========================================================================
    public function ddJobtypes()
    {
         $jobtype = jobtype::Select('id' ,'name')->get();
         return json_encode($jobtype);
         return $jobtype;
    }
//==========================================================================    
    public function StoreJob(Request $request)
    { 
          //return $request;
          
          $cid=$request->input("cid");
          $skillsArray= $request->input('Skills');
          $respoArray= $request->input('Respo');
          $skills="";
          $respo="";
          $jobId=job::where('CID',$cid)->max('id')+1;
          for($i=0;$i<count($skillsArray);$i++){
              $skills.="*".$skillsArray[$i];
          }
        for($i=0;$i<count($respoArray);$i++){
              $respo.="*".$respoArray[$i];
          }
          job::create([
                'cid'=> $cid , 'Name' => $request->input('Name'),
                'Job_Type'=>$request->input('Job_Type') ,'Respo' => $respo, 'Skills' => $skills ,'d1'=>DB::raw('curdate()')
                ,'id'=>$jobId]);
                return 1;
    }

//==========================================================================
    public function editjob(Request $request)
    {
         
        $cid = $request->input("CID");
        $id = $request->input("id");
        $Job=Job::find($id);
       if( DB::table('jobs')->find($id) && DB::table('jobs')->find($cid) != null)
            { 
               
                $name = $request->input("Name") ;
                $jobType = $request->input("Job_Type");
                $en = $request->input("en");
                $respo = $request->input("Respo");
                $skills = $request->input("Skills");
                $d1 = $request->input("d1");
               
            $Job->Name = $name;
            $Job->Job_Type = $jobType;
            $Job->en = $en;
            $Job->Respo = $respo;
            $Job->Skills = $skills;
            $Job->d1 = $d1;
            $Job->save();
                 
              
            }
    }
//===========================================================================

    public function deletejob(Request $request)
     {
        $cid = $request->input("cid");
        $id = $request->input("id");
        if(DB::table('jobs')->find($id) && DB::table('cid')->find($cid) != null)
            { 
             DB::table('jobs')->where('id',$id,'cid',$cid)->update(['en'=>0]) ;
            }
        else 
            {
             return 0;
            }
        return 1;
     }
    
}
