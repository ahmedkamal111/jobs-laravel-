<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\jobtype;
use App\Job;
use App\Company;
use App\candicount;
use App\InterviewTime;

class TimeController extends Controller
{
  public function storetime(Request $request)
    {
        /*request()->merge(['Name' => 'Flutter Developer']);
        request()->merge(['date' => '2019-05-01']);
        request()->merge(['time' => '15:40:32']);
        request()->merge(['duration' => '20']);
        request()->merge(['Candi_count' => '4']);*/
        //validation is not completed
        $cid=$request->input("CID");     
        $validate = request()->validate([
               'Name' => 'required',
                'date' => 'required',
                'time' => 'required',
                'duration' => 'required',
                'Candi_count' => 'required'
        ]);
          // return $validate->name;
        $job_id = Job::where('name','=',request('Name'))->value('id');
        $interviewTimeId=InterviewTime::where('CID',$cid)->max('id');
        for($i=1 ; $i<= request('Candi_count');$i++)
        {
            $interviewTimeId++;
            InterviewTime::create(['cid' => $cid , 'job_id'=>$job_id ,'date'=>request('date'),'time'=>request('time') ,'duration'=>request('duration'),'id'=>$interviewTimeId]);
        }
            $candicountId=candicount::where('CID',$cid)->max('id');
            candicount::create(['cid' => $cid, 'job_id'=>$job_id ,'date'=>request('date'),'time'=>request('time') ,'duration'=>request('duration'),'candicount'=>request('Candi_count'),'id'=>$candicountId]);
      }
//======================================================================
  public function show_intime_admin(Request $request)
    {
      $cid=$request->input('cid');
      $time=$request->input('time');
      $date=$request->input('date');
      $job_name=$request->input('job_name');
      if($time == " " and $date == " " and $job_name == " ")
          {
            $row = candicount::where('cid','=',$cid)->get();

          }
          else if( $job_name == " ")
          {
            $row = candicount::where([['cid','=',$cid],['time','=',$time],['date','=',$date]])->get();
          }
           else if( $time == " " and $date ==" ")
          {
            $job_id =  Job::Select('id')->where('Name','=',$job_name)->value('id');
            $row = candicount::where([['cid','=',$cid],['job_id','=',$job_id]])->get();
          }
          else
          {
            $job_id =  Job::Select('id')->where('Name','=',$job_name)->value('id');
            $row = candicount::where([['cid','=',$cid],['time','=',$time],['date','=',$date],['job_id','=',$job_id]])->get();
          }
          for($i=0;$i<count($row);$i++)
              $row[$i]['flag'] = $flag;
          return $row;
      //return view('update',compact('row'));
  }
//======================================================================
  public function show_intime_candi(Request $request)
    {
       /*request()->merge(['cid' => '1']);
        request()->merge(['time' => '15:40:32']);
        request()->merge(['date' => '2019-05-01']);
        request()->merge(['job_name' => 'Flutter Developer']);
        request()->merge(['flag' => '1']);*/
      $cid=$request->input('cid');
      $time=$request->input('time');
      $date=$request->input('date');
      $job_name=$request->input('job_name');
      $flag=$request->input('flag');
      $newJson=[];
      
      if($time == " " and $date == " " and $job_name == " ")
          {
            $row = candicount::where('cid','=',$cid)->get();
             
          }
          else if( $job_name == " ")
          {
            $row = candicount::where([['cid','=',$cid],['time','=',$time],['date','=',$date]])->get();
           
          }
           else if( $time == " " and $date ==" ")
          {
            $job_id =  Job::Select('id')->where('Name','=',$job_name)->value('id');
            $row = candicount::where([['cid','=',$cid],['job_id','=',$job_id]])->get();
             
          }
          else
          {
            $job_id =  Job::Select('id')->where('Name','=',$job_name)->value('id');
            $row = candicount::where([['cid','=',$cid],['time','=',$time],['date','=',$date],['job_id','=',$job_id]])->get();
          }
          for($i=0;$i<count($row);$i++){
            $arr = InterviewTime::where([['cid','=',$row[$i]->cid ] , ['job_id','=',$row[$i]->job_id] , ['date','=',$row[$i]->date] , ['time','=',$row[$i]->time ]
            ,['duration','=',$row[$i]->duration] ])->orderBy('regist_time', 'DESC')->get();
            for($j=0;$j<count($arr);$j++)
            {
              if($arr[$j]->candi_id=== NULL)
              {
                array_push($newJson,$row[$i]);
                $row[$i]['flag'] = $flag;
                break;
              }
            }
          }
          return $newJson;
      //return view('update',compact('row'));
  }
//======================================================================
  public function edit_time(candicount $candicount)
    {
   /*request()->merge(['date' => '2019-12-01']);
   request()->merge(['time' => '15:20:32']);
   request()->merge(['duration' => '20']);
   request()->merge(['candicount' => '8']);*/
   $validate = request()->validate([
              'date' => 'required',
              'time' => 'required',
              'duration' => 'required',
              'candicount' => 'required'
      ]);
      if($candicount->candicount < request('candicount'))
          {
              for($i=$candicount->candicount; $i<request('candicount') ;$i++)
              {
                    InterviewTime::create(['cid' => $candicount->cid , 'job_id'=>$candicount->job_id ,'date'=>$candicount->date,'time'=>$candicount->time ,'duration'=>$candicount->duration]);
              }

           }
      else if ($candicount->candicount > request('candicount'))
      {
          $arr = InterviewTime::where([ ['cid','=',$candicount->cid ] , ['job_id','=',$candicount->job_id] , ['date','=',$candicount->date] , ['time','=',$candicount->time ] , ['duration','=',$candicount->duration] ])->orderBy('regist_time', 'DESC')->get();
           for($i=0 ; $i<$candicount->candicount - request('candicount') ; $i++)
                  {
                      if(!empty($arr[$i]->candi_id ))
                          {
                              $arr[$i]->delete();
                              //sendemail
                          }
                       else{
                           $arr[$i]->delete();
                          }
                      }
      }
         InterviewTime::where([['cid','=',$candicount->cid],['date','=',$candicount->date],['time','=',$candicount->time],['duration','=',$candicount->duration]])->update(['date'=>request('date'),'time'=>request('time'),'duration'=>request('duration')]);
        var_dump ($candicount->update(request(['date','time','duration','candicount'])));
        //  return $candicount;

      }
//======================================================================
  public function delete_time(candicount $candicount)
    {

    $arr = InterviewTime::where([ ['cid','=',$candicount->cid ] , ['job_id','=',$candicount->job_id] , ['date','=',$candicount->date] , ['time','=',$candicount->time ] , ['duration','=',$candicount->duration] ])->orderBy('regist_time', 'DESC')->get();
     for($i=0 ; $i<count($arr); $i++)
            {
                if(!empty($arr[$i]->candi_id ))
                    {
                        $arr[$i]->delete();
                        //sendemail
                    }
                 else{
                     $arr[$i]->delete();
                    }
                }
          $candicount->delete();

  }
  //============================================================================================
  public  function update_candi_time(Request $request)
  {
    $oldCandiCountId=$request->input('oldCandiCountId');
    $newCandiCountId=$request->input('newCandiCountId');
    $candId=$request->input('candId');
    InterviewTime::where([['id','=',$newCandiCountId]])->update(['candi_id'=>$candId]);
    InterviewTime::where([['id','=',$oldCandiCountId]])->update(['candi_id'=>NULL]);
  }
  //============================================================================================
  public  function cancel_candi_time(Request $request)
  {
    $oldCandiCountId=$request->input('oldCandiCountId');
    $candId=$request->input('candId');
    InterviewTime::where([['id','=',$oldCandiCountId]])->update(['candi_id'=>NULL]);
  }
  //=============================================================================================
  public  function showCandiTime(Request $request)
  {
     $jobId=$request->input('jobId');
     $CID=$request->input('CID');
     $candiId=$request->input('candId');
     $flag=$request->input('flag');
     $arr=InterviewTime::where("candi_id",$candiId)->where("job_id",$jobId)->where("CID",$CID)->first();
     $arr['flag']=$flag;
     return $arr;
  }
}
