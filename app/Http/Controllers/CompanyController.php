<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Company;
use App\CompanyJob;
use App\usrs;
use File;
use Response;
class CompanyController extends Controller
{
    public function detectCompany($random,$cmp)
    {
        $url= "/".$random."/".$cmp;
       // return $url;
        
        $company = Company::where("url", "=",  $url)->get(); //http://joblaravel.tbv.cloud/aa/Teqneia
       
        $company = count($company) != 1 ? null : $company[0];
        //$company=collect($company);
        //$company = $company->except('welcome');
        $response = [];
        if($company) {
            $companyJob=$company->CompanyJob;
            $response['success'] = true;
            $response['company'] = $company;
        }
        else
        {
            $response['success'] = false;
        }
        return response()->json(compact('response'));
    }
    public function addCompany(Request $request){
      $request->merge([
            'c1'=>'9F81F7',
            'c2'=>'9F81F7',
            'c3'=>'FFFFFF'
        ]);
      $userId= $request->input('userId');
      $profile=usrs::select('uprofile')->where('usr_id','=',$userId)->value('uprofile');
      if($profile!=1)
        return "-1";
         $validatedData = $request->validate([
           'logo' => 'image|max:1999',
           'c1' => 'required',
           'c2' => 'required',
           'c3' => 'required'
         ]);
         $storePath = "/home/tanta/media.tbv.cloud/";
         $logo=$request->file("logo");
         $company=new Company();
        if($request->hasFile("logo")) 
            {
                $file_src=$request->file('logo');
                $filenameWithExt = $file_src->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = strtolower($file_src->getClientOriginalExtension());
                $fileNameToStore= $request->input('Name').'_'.time().'.'.$extension;
                                //return $storePath.$fileNameToStore;
                $fileContent=file_get_contents($file_src);
                $is_file_uploaded = file_put_contents($storePath.$fileNameToStore,$fileContent);
                $company->logo=$fileNameToStore;
                if(!$is_file_uploaded){
                    return Redirect::back()->withErrors(['msg'=>'file failed to uploaded on dropbox']);
                }
            }
        $company->Name=$request->input('Name');
        $company->url=$request->input('url');
        $company->payment_id=$request->input('payment_id');
        $company->d_end=$request->input('d_end');
        $company->c1=$request->input('c1');
        $company->c2=$request->input('c2');
        $company->c3=$request->input('c3');
        $company->homepage=$request->input('homepage');
        $company->login_branding=$request->input('login_branding');
        $company->support_email=$request->input('support_email');
        $company->welcome=$request->input('welcome');
        $company->Facebook=$request->input('Facebook');
        $company->Twitter=$request->input('Twitter');
        $company->LinkedIn=$request->input('LinkedIn');
        $company->save();
        $companyJob=new CompanyJob();
        $companyJob->cid=$company->cid;
        $companyJob->jslogan=$request->input('jslogan');
        $companyJob->jwelcome=$request->input('jwelcome');
        $companyJob->save();
        return $company;
        }
        
    public function showCompanies(Request $request)
        {
         return Company::All();
        }

    public function updateCompany(Request $request) 
        {
         $updatedCompanyId=$request->input('updatedCompanyId');
         $company=Company::find($updatedCompanyId);
         $company->Name=$request->input('companyName');
         $company->url=$request->input('url');
         $company->c1=$request->input('c1');
         $company->c2=$request->input('c2');
         $company->c3=$request->input('c3');
         $company->homepage=$request->input('homepage');
         $company->login_branding=$request->input('login_branding');
         $company->support_email=$request->input('support_email');
         $company->welcome=$request->input('welcome');
         $company->save();
        }
        
    public function deleteCompany(Request $request){
         $request['updatedCompanyId']=15;
         $updatedCompanyId=$request->input('updatedCompanyId');
         $company=Company::find($updatedCompanyId);
         $company->delete();
    }
    public function get($path){
        $path="/home/tanta/media.tbv.cloud/".$path;
        if (!file_exists($path))
            abort(404);
        $file=file_get_contents($path);
        
        $arr= explode(".", $path);
        $extension = strtolower($arr[count($arr)-1]);
        $type=self::he5a($extension);
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


}
