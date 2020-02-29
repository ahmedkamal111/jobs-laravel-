<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\TokenDecoded;
use App\TokenEncoded;
use App\JWT;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Candidate;
use App\candi_job;
use App\Company;
use Carbon;
use App\Job;
use App\usrs;
use App\candi_ev_channels;
use App\candi_ev_levels;
use App\candi_eval;

class candi_evaluation_Controller extends Controller
{
     public function showchannels(Request $request)
        {
         return candi_ev_channels::All();
        }
        
     public function showlevels(Request $request)
        {
         return candi_ev_levels::All();
        }

     public function addevaluation(Request $request)
     {
        $candi_job_id=$request->input("candi_job_id");
        $cid = $request->input("cid");
        $ch = $request->input("ch");
        $txt = $request->input("txt");
        $ev=$request->input("ev");
        $def_ev=$request->input("def_ev");
        $id=candi_eval::where('CID',$cid)->max('id')+1;
      
         $tokenEncoded = new TokenEncoded($request->header('Authorization'));
         $publicKey = file_get_contents('/home/tanta/joblaravelTest.tanta.club/rsa.public');
         $tokenEncoded->validate($publicKey, JWT::ALGORITHM_RS256);
         $tokenDecoded = $tokenEncoded->decode();
         $payloadable = $tokenDecoded->getPayload();
         $email= $payloadable['email'];
         $user_id=usrs::select('usr_id')->where('email','=',$email)->where('CID','=',$cid)->value('usr_id');
         $d1 = Carbon\Carbon::now();
        if($def_ev==1) 
        {
        $final_eval=candi_eval:: where('cid', $cid)
                            ->where('candi_job_id',$candi_job_id)
                            ->update(array('def_ev'=>'0' ));
                            
         $candi_eval=candi_eval::insert( ['CID'=>$cid,'id'=>$id,'candi_job_id'=>$candi_job_id,'usr'=>$user_id,'d1'=>$d1,'ch'=>$ch,'ev'=>$ev,'txt'=>$txt,'def_ev'=>$def_ev]);
        }
        else
        { 
            $candi_eval=candi_eval::insert( ['CID'=>$cid,'id'=>$id,'candi_job_id'=>$candi_job_id,'usr'=>$user_id,'d1'=>$d1,'ch'=>$ch,'ev'=>$ev,'txt'=>$txt,'def_ev'=>$def_ev]);
        }
         return 1;
         
     }
     public function showevaluation(Request $request)
     {
        // return $request;
     $candi_job_id=$request->input("candi_job_id");
     $cid = $request->input("cid");
     $tokenEncoded = new TokenEncoded($request->header('Authorization'));
         $publicKey = file_get_contents('/home/tanta/joblaravelTest.tanta.club/rsa.public');
         $tokenEncoded->validate($publicKey, JWT::ALGORITHM_RS256);
         $tokenDecoded = $tokenEncoded->decode();
         $payloadable = $tokenDecoded->getPayload();
         $email= $payloadable['email'];
         $user_name=usrs::select('Name')->where('email','=',$email)->where('CID','=',$cid)->value('Name');
        
     $evaluation =candi_eval::select('cid', 'id', 'candi_job_id', 'd1', 'ch', 'ev', 'txt', 'def_ev')
                           ->where('cid', $cid)
                           ->where('candi_job_id',$candi_job_id)
                           ->get();
    return   json_encode (array($evaluation,'usr_name'=>$user_name));
         
     }
  
}
