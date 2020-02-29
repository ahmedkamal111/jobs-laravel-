<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\interview_status;
use App\InterviewTime;
use App\Candidate;
use App\Company;
use App\candi_job;
use App\Job;
use App\candicount;
use Carbon\Carbon;
use App\Candi_app_status;
class Candi_app_status_Controller extends Controller
{
  public function show()
  {
        return Candi_app_status::All();
  }
//==================================================================
 public function applyTimeInterview(Request $request)
      {
        $candiCountsId=$request->input('candiCountsId');
        $candiId=$request->input('candiId');
        $candiCount=candicount::find($candiCountsId);
        $times = InterviewTime::where([['cid','=',$candiCount->cid ] , ['job_id','=',$candiCount->job_id] , ['date','=',$candiCount->date] , ['time','=',$candiCount->time ]
        ,['duration','=',$candiCount->duration] ])->orderBy('regist_time', 'DESC')->get();
        for($j=0;$j<count($times);$j++)
        {
          if($times[$j]->candi_id===NULL)
          {
              $times[$j]->update(['regist_time' => Carbon::now()]);
              $times[$j]->update(['candi_id' => $candiId]);
              //time stamp
              //return $candiId.$candiCount->job_id;
              //return $candiJobId=candi_job::select('id')->where('Candi_id',$candiId)->where('Job_id',$candiCount->job_id)->value('id');
              candi_job::where('id', $candiJobId)->update(['st' => 7]);
              break;
          }
        }
    }
     
  
//==================================================================
  public function update(Request $request)
    {
        
        $candiId=$request->input('candiId');
        $CID=$request->input('CID');
        $interviewStatus=$request->input('status');
        $jobId=$request->input('jobId');
        $candiJobId=candi_job::where('Candi_id',$candiId)->where('Job_id',$jobId)->get('id')[0]['id'];
      
        //$interviewStatusId= Candi_app_status::where('name',$interviewStatus)->get('id')[0]['id'];
       
        $companyName=Company::where('cid',$CID)->get('name')[0]['name'];
        $companyEmail=Company::where('cid',$CID)->get('support_email')[0]['support_email'];
        $candiEmail=Candidate::where('id',$candiId)->get('email')[0]['email'];
        if($interviewStatus==6)
        { //invited
            $randomNumber= self::generteRandomNumber();
            $url="http://joblaravel.tbv.cloud/".$companyName.'/'.$candiId.'/'.$jobId.'/'.$randomNumber;
            //candi_jobs::where('id', $candiJobId)->update(['url' => $url]);
            candi_jobs::where('id', $candiJobId)->update(['randomNumber' => $randomNumber]);
            self::sendMail($companyEmail,$candiEmail,$url);
            //return $url;
        }
        candi_job::where('id', $candiJobId)->update(['st' => $interviewStatus]);
        return 1;
  }
//==================================================================
// public function showurl($companyName,$candiId,$jobId,$randomNumber)
//     {
//         $expectedRandomNumber=candi_job::where('Candi_id',$candiId)->where('Job_id',$jobId)->get('randomNumber')[0]['randomNumber'];
//         $CID=Company::where('Name',$companyName)->get('cid')[0]['cid'];
//         $job_name=Job::where('id',$jobId)->get('name')[0]['name'];

//         if($expectedRandomNumber!=$randomNumber){
//           return "not found";
//         }
//         $candiInterStatus=candi_job::where('Candi_id',$candiId)->where('Job_id',$jobId)->get('inter_status_id')[0]['inter_status_id'];
//         return redirect()->route('show_intime_candi', array('cid' => $CID, 'time' => ' ','date' => ' ','job_name' =>$job_name,'flag' =>$candiInterStatus-1));
//     }
//==================================================================
public function showurl($companyName,$candiId,$jobId,$randomNumber)
    {
        $expectedRandomNumber=Candidate::where('Candi_id',$candiId)->get('randomNumber')[0]['randomNumber'];
        $CID=Company::where('Name',$companyName)->get('cid')[0]['cid'];
        $job_name=Jobs::where('id',$jobId)->get('name')[0]['name'];
        if($expectedRandomNumber!=$randomNumber){
          return "not found";
        }
        $candiInterStatus=candi_jobs::where('Candi_id',$candiId)->where('Job_id',$jobId)->get('st')[0]['st'];
        if($candiInterStatus!=6)
          return redirect()->route('show_intime_candi', array('cid' => $CID, 'time' => ' ','date' => ' ','job_name' =>$job_name,'flag' =>$candiInterStatus-1));
        else if($candiInterStatus==6){
          return redirect()->route('showCandiTime', array('candiId' => $candiId, 'jobId' =>$jobId,'CID' =>$CID,'flag' =>$candiInterStatus-1));
        }
    }
//====================================================
  private function generteRandomNumber()
    {
        $s="";
        for($i=0;$i<16;$i++)
        {
          $s.=strval(rand(1,9));
        }
        return $s;
  }
//==================================================================
  private function sendMail($companyEmail,$candiEmail,$url)
    {
        $frm=$companyEmail;
        $to=$candiEmail;
        $cc="";
        $content= '<html>'.
                     '<b>URL:</b>' .'&ensp;'   .$url.  '<br>'.
                  '</html>';
                    \Mail::send(array(),
                array(
                   'name' => '',
                   'email' => $frm,
                ), function($message) use ($content,$to,$frm)
               {
               $message->setBody($content, 'text/html');
               $message->from($frm);
               $message->to($to, 'Admin')
               ->subject('new job for you');
              });
              //$response["ok"] = true;
  }
}
