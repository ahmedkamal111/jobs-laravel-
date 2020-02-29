<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Mail;
use Illuminate\Http\Request;
use App\Candidate;
use App\candi_job;
use App\Company;
use File;
use Response;
use Carbon;
use App\Job;
use App\candi_arch;
use App\TokenDecoded;
use App\TokenEncoded;
use App\JWT;
use App\usrs;
use App\candi_ev_channels;
use App\candi_ev_levels;
use App\candi_eval;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
        public function store(Request $request) {
            //return "5555";
            $errorCode="0";//no errors 0    error in upload 2     error in new record 1
            $fileNameToStore="";
            $file_src="";
            $is_file_uploaded=false;
            $url = "cvs/";
            $cid = $request->input("CID");
            $dob = $request->input("Dateofbirth");
            $email = $request->input("Email");
            $gender = $request->input("Gender");
            //$jobType = $request->input("JobType");
            $linkedIn = $request->input("LinkedIn");
            $location = $request->input("Location");
            $mobile = $request->input("Mobile");
            $name = $request->input("Name"); 
            $cv_link = $request->input("cv_link");
            $cv = $request->file("ff");
            $salary = $request->input("salary");
            $university = $request->input("university");
            $job_id = $request->input("jobId");
            $Candi_id=Candidate::where('CID',$cid)->max('id')+1;
            $company=Company::where('cid',$cid)->first();
            $candidate = new Candidate();
            $candidate->id=$Candi_id;
            $candidate->CID = $cid ? $cid : 1;
            $filesNames = array();
            $candidate->name = $name;
            $candidate->email = $email;
            $candidate->Gender = $gender;
            $candidate->Loc = $location;
            $candidate->cv_link  = $cv_link;
            $candidate->mobile = $mobile;
            $candidate->linkedin = $linkedIn;
            $candidate->university = $university;
            //$candidate->salary = $salary;
            $candidate->dob = $dob;
            $candidate->ds = Carbon\Carbon::now();
            $candiJobId=candi_job::where('CID',$cid)->max('id')+1;
            $candiJob= new candi_job();
            $candiJob->CID=$cid ? $cid : 1;
            $candiJob->Candi_id=$Candi_id;
            $candiJob->Job_id=$job_id;
            $candiJob->salary = $salary;
            $candiJob->id=$candiJobId;
            $jobType=Job::select('Job_Type')->where('id',$job_id)->value('Job_Type');
            //return $jobType;
            if(empty($email)||empty($mobile)||empty($name)||empty($gender))
                $errorCode="1";
            else if($request->hasFile("ff")) 
            {
                $file_src=$request->file('ff');
                $filenameWithExt = $file_src->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = strtolower($file_src->getClientOriginalExtension());
                $fileNameToStore=$candidate->CID.'_'.$candidate->id.'_'.'1'.'.'.$extension;
                $candidate->cv_file = $fileNameToStore;
            }
            self::sendMail($company,$candidate,$jobType,$job_id,$salary);
            if($errorCode=="0"){
                $candidate->save();
                $candiJob->save();
            }
            if($request->hasFile("ff")&& $errorCode=="0") 
            {
                $is_file_uploaded = Storage::disk('dropbox')->put($fileNameToStore,file_get_contents($file_src));
                if(!$is_file_uploaded){
                        $errorCode="2";
                        //return Redirect::back()->withErrors(['msg'=>'file failed to uploaded on dropbox']);
                }
            }
            return response()->json(compact('errorCode'));

        }
        public function deleteCompletely(Request $request)
        {
             $tokenEncoded = new TokenEncoded($request->header('Authorization'));
             $publicKey = file_get_contents('/home/tanta/joblaravelTest.tanta.club/rsa.public');
             $tokenEncoded->validate($publicKey, JWT::ALGORITHM_RS256);
             $tokenDecoded = $tokenEncoded->decode();
             $payloadable = $tokenDecoded->getPayload();
             $CID=$request->input('CID');
             $email= $payloadable['email'];
             $user_id=usrs::select('usr_id')->where('email','=',$email)->where('CID','=',$CID)->value('usr_id');
            $candiId=$request->input('candiId');
            $candijobid=candi_job::select('id')->where('Candi_id',$candiId)->where('CID',$CID)->value('id');
            //return $candijobid;
            $dd = Carbon\Carbon::now();
            $candi=Candidate::where('id',$candiId)->where('CID',$CID)->first();
            $candi_arch=candi_arch::insert( ['CID'=>$candi['CID'],'id'=>$candi['id'],'name'=>$candi['name'],'email' => $candi['email'],'Gender'=>$candi['Gender'],'Loc'=>$candi['Loc'],'cv_link'=>$candi['cv_link'],'dob'=>$candi['dob'],'mobile'=>$candi['mobile'],'linkedin'=>$candi['linkedin'],'university'=>$candi['university'],'ds'=>$candi['ds'],'del_by'=>$user_id,'dd'=>$dd]);
            $path=$candi->cv_file;
            //return $path;
            if (Storage::disk('dropbox')->exists($path)){
                if(!Storage::disk('dropbox')->delete($path)){
                    return "0";
                }
            }
            $candi->delete();
            $candiJob=candi_job::where('Candi_id',$candiId)->where('CID',$CID);
            $candiJob->delete();
            $candieval=candi_eval::where('candi_job_id',$candijobid)->where('cid',$CID);
            $candieval->delete();
            return 1;
        }
        public function deletePartially(Request $request) {
           // return $request;
            $candiId=$request->input('candiId');
            $jobId=$request->input('jobId');
            $CID=$request->input('CID');
            $candijobid=candi_job::select('id')->where('Candi_id',$candiId)->where('CID',$CID)->value('id');
            $candieval=candi_eval::where('candi_job_id',$candijobid)->where('cid',$CID);
            $candieval->delete();
            $candiJob=candi_job::where('CID',$CID)->where('Candi_id',$candiId)->where('Job_id',$jobId)->first();
            $candiJob->delete();
            
            return 1;
        }
        public function shiftData(Request $request) {
            $candies=Candidate::orderBy('id', 'asc')->get();
            for($i=1;$i<count($candies);$i++){
                if($candies[$i]->id-1!=$candies[$i-1]->id){
                    $newId=$candies[$i-1]->id+1;
                    $candiJob=candi_job::where('Candi_id',$candies[$i]->id)->where('CID',$candies[$i]->CID)->get();
                    $candies[$i]->id=$newId;
                    $oldCv=$candies[$i]->cv_file;
                    $arrayCv=explode("_",$oldCv);
                    $newCv="";
                    if(count($arrayCv)>=3){
                        $newCv=$arrayCv[0].'_'."$newId".'_'.$arrayCv[2];
                        $candies[$i]->cv_file=$newCv;
                    }
                    $candies[$i]->save();
                    if((Storage::disk('dropbox')->exists($oldCv))){
                        Storage::disk('dropbox')->move($oldCv, $newCv); 
                    }
                    for($i=0;$i<count($candiJob);$i++){
                        $candiJob[$i]->Candi_id=$newId;
                        $candiJob[$i]->save();
                    }
                }
            }
        }

        public function get($path){
            if (!Storage::disk('dropbox')->exists($path))
                  abort(404);
            $file= (Storage::disk('dropbox')->get($path));
            $arr= explode(".", $path);
            $extension = strtolower($arr[count($arr)-1]);
            $type=self::he5a($extension);
            //return $type;
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
        public function he5a($filename){
          		$ext = strtolower($filename);
          		if (!(strpos($ext, '.') !== false)) {
          			$ext = '.' . $ext ;
          		}
          		switch ($ext) {

          			case '.docx': $mime ='application/vnd.openxmlformats-officedocument.wordprocessingml.document'; break; // Microsoft Word (OpenXML)
          			
          			
          			case '.gif': $mime ='image/gif'; break; // Graphics Interchange Format (GIF)
          			case '.htm': $mime ='text/html'; break; // HyperText Markup Language (HTML)
          			case '.html': $mime ='text/html'; break; // HyperText Markup Language (HTML)
          			case '.ico': $mime ='image/x-icon'; break; // Icon format

          			case '.jpeg': $mime ='image/jpeg'; break; // JPEG images
          			case '.jpg': $mime ='image/jpeg'; break; // JPEG images
          			case '.png': $mime ='image/png'; break; // Portable Network Graphics

          			case '.json': $mime ='application/json'; break; // JSON format
          			case '.pdf': $mime ='application/pdf'; break; // Adobe Portable Document Format (PDF)
          			case '.ppt': $mime ='application/vnd.ms-powerpoint'; break; // Microsoft PowerPoint

          			case '.txt': $mime ='text/plain'; break; // Text, (generally ASCII or ISO 8859-n)

          			case '.xml': $mime ='application/xml'; break; // XML

          			case '.7z': $mime ='application/x-7z-compressed'; break; // 7-zip archive*/

          			default: $mime = 'application/octet-stream' ; // general purpose MIME-type
          		}
          		return $mime ;
          }

        //===================================================
        private function sendMail($company,$candidate,$jobType,$job_id,$salary)
        {
            $companyEmail=$company->support_email;
            $companyLogo=$company->logo;
            $candiJob = $candidate->getJob(explode(",",$job_id));
            $jobType= ($candiJob[0]->Job_Type);
            $countJobs = count($candiJob);
            $candidateJobs = "";
            for($i = 1; $i <= $countJobs; $i++) {
                $candidateJobs .= $candiJob[$i-1]->Name . ($i == $countJobs ? "" : ", ");
            }
            $subject="Job :  ".$candidateJobs.",new candidate";
            if($jobType=="5")
                $subject="internship : ".$candidateJobs." , new candidate";

            $frm=Array("noreply@TBV.cloud"=>"TBV Jobs");

            $to="hosam.elkashab222@gmail.com";
            $cc="";
            $content= 
                '<html>'  .
                '<style>
                table, th, td {
                  text-align: left;
                }
                </style>
                
                <table>
                    <td>ID</td>
                    <td>'.$candidate->id.'</td>
                  </tr>
                  <tr>
                    <td>Name</td>
                    <td>'.$candidate->name.'</td>
                  </tr>
                  <tr>
                    <td>Job</td>
                    <td>'.$candidateJobs.'</td>
                  </tr>
                    <tr>
                    <td>Mobile</td>
                    <td>'.$candidate->mobile.'</td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td>'.$candidate->email.'</td>
                  </tr>
                  <tr>
                    <td>CV</td>
                    <td>'.$candidate->cv_file.'</td>
                  </tr>
                  <tr>
                    <td>cv_link</td>
                    <td>'.$candidate->cv_link.'</td>
                  </tr>
                  <tr>
                    <td>date of birth</td>
                    <td>'.$candidate->dob.'</td>
                  </tr>
                  <tr>
                    <td>LinkedIn</td>
                    <td>'.$candidate->linkedin.'</td>
                  </tr>
                  <tr>
                    <td>Expected salary   </td>
                    <td>'.$salary.'</td>
                  </tr>
                  <tr>';
                  if(count($candidate->location()->get())==0){
                        $content.='<td>Location</td>
                                   <td>'.'</td>
                                   </tr>';
                 }
                 else{
                        $content.='<td>Location</td>
                                   <td>'.$candidate->location()->get()[0]->Name.'</td></tr>
                                   <tr>';
                 }
                 if(count($candidate->university()->get())==0){
                        $content.='<td>university</td>
                                   <td>'.'</td>
                                   </tr>';
                 }
                 else{
                        $content.'<td>university</td>
                                      <td>'.$candidate->university()->get()[0]->Name.'</td></tr>';
                }
                $content.='</table>'.
                          '<br />'.
                          'thank you for visiting our website   '.
                          '<a href=https://jobs.tbv.cloud'.$company->url.'>Join Us</a>'.
                          '<br />'.'<br />'.'<br />'.'<br />'.
                          '<img style="width: auto; height:200px;" src="http://joblaravel.tbv.cloud/tt/logo/TBV_Logo.png">'.
                          '</html>';
                                        \Mail::send(array(),
                                    array(
                                       'name' => $candidate->name,
                                       'email' => $frm,
                                    ), function($message) use ($content,$to,$frm,$subject)
                                   {
                                   $message->setBody($content, 'text/html');
                                   $message->from($frm);
                                   $message->to($to, 'Admin')
                                   ->subject($subject);
                                  });
        }
}
