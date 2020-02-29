<?php

namespace App\Http\Controllers;
use App;
use App\TokenDecoded;
use App\TokenEncoded;
use App\JWT;
use App\usrs;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

class LoginController extends Controller
{
    public function enterEmail(Request $request) // check if the mail is existed to pass him to password layout
    {
          $request->merge([
                      //'CID'=>'1',
                     // 'email'=>'alaa.abobakr1994@gmail.com',
                  ]);
         $email = $request->input('email'); // validate mail
         $cid = $request->input('CID'); 
         //return $cid;
         $check =usrs::select('email')->where('email','=',$email)->where('CID','=',$cid)->value('email'); // check mail
        if(!empty($check)) // pass to password
        {
           $encrypted_password = usrs::Select('pw')->where( 'email','=',$email )->value('pw');//retrieve password
    		 if($encrypted_password==NULL){
    		      self::createPin($request);
    		     return 2;//first time to login
    		 }
           return 1; //email is found
        }
        else   // take user back to mail
        {
           return 0; //email is not found
        }
    }

	public function login(Request $request)
    {
        $email = $request->input('email');
	    $inputPassword=$request->input('pw');
		$realPassword = usrs::Select('pw')->where( 'email','=',$email )->value('pw');//retrieve password
		$duration=100000000;
		if(Hash::check($inputPassword, $realPassword)){
		    $expirationTime=time()+$duration*60;
            $user=usrs::where([['email','=',$email]])->first();
            $user=collect($user);
            $user = $user->except('PIN');
            //return $user;
            $payloadable = [
                    'name' => $user['Name'],
                    'email' => $user['email'],
                    'iat' => $expirationTime,
                ];
                $tokenDecoded = new TokenDecoded([], $payloadable);
                $privateKey =file_get_contents('/home/tanta/joblaravelTest.tanta.club/rsa.private');
                $tokenEncoded = $tokenDecoded->encode($privateKey, JWT::ALGORITHM_RS256)->__toString();
                $payloadable['iat']=time()+24*60*60;
                $refreshToken = new TokenDecoded([], $payloadable);
                $refreshToken = $refreshToken->encode($privateKey, JWT::ALGORITHM_RS256)->__toString();
                return response()->json(compact('refreshToken','tokenEncoded','user','duration'));
		 }
         else{
             return -1;//password not correct
         }
    }

	public function createPin(Request $request)
    {
		      //if password is forgetten
		   $CID=$request->input('CID');
			 $companyEmail=Company::select('support_email')->where('cid',$CID)->value('support_email');
			 $email = $request->input('email');
		   if(usrs::where('email','=',$email)->value('email')) // if succeded
				{
					$pin = rand(99999,1000000); // create random number
					usrs::where('email','=',$email)->update(['PIN' => $pin]); // then update the PIN
					self::sendMail($companyEmail,$email,$pin,$request->input('name')) ;
		            return 2;	// and retun him to new page to insert the PIN and his new Paasword
				}
				else { // it is show him the below message to get access
					return -1 ;
				}
    }
    public function resetPassword(Request $request)
    {
		$email = $request->validate(['email' => 'required|email']);
        $PIN = usrs::where('email','=',$email)->value('PIN'); // return PIN to check it with upcoming Request
		$request->validate(['pw' => 'required']);
		$pw = bcrypt($request->pw);
		$user=usrs::where([['email','=',$email]])->first();
        if($PIN == request('PIN'))
    	{
    	   $duration=100000000;
				$expirationTime=time()+$duration*60;
                $user=collect($user);
                $user = $user->except('PIN');
                //return $user;
                $payloadable = [
                            'name' => $user['Name'],
                            'email' => $user['email'],
                            'iat' => $expirationTime,
                ];
                $tokenDecoded = new TokenDecoded([], $payloadable);
                $privateKey =file_get_contents('/home/tanta/joblaravelTest.tanta.club/rsa.private');
                $tokenEncoded = $tokenDecoded->encode($privateKey, JWT::ALGORITHM_RS256)->__toString();
                $payloadable['iat']=time()+24*60*60;
                $refreshToken = new TokenDecoded([], $payloadable);
                $refreshToken = $refreshToken->encode($privateKey, JWT::ALGORITHM_RS256)->__toString();
                usrs::where('email','=',$email)->update(['pw' => $pw]);
                return response()->json(compact('refreshToken','tokenEncoded','user','duration'));

            /*
            self::sendMail($companyEmail,$email,$pin,$request->input('name')) ;
            $pin = rand(99999,1000000); // create random number
            $api_token=self::quickRandom();
            usrs::where('email','=',$email)->update(['pw' => $pw]);
    			  return $check =usrs::select('CID','usr_id','priv','uprofile','email')->where([ ['email','=',$email],['pw','=',$pw]])->get();*/
    	}
    	else{
    		return -1;
    	}
    }
    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
    private function sendMail($companyEmail,$userEmail,$pin,$userName)
    {
        $frm=$companyEmail;
        $to=$userEmail;
        $cc="";
        $content= '<html>'.
                     '<b>PIN:</b>' .'&ensp;'   .$pin.  '<br>'.
                  '</html>';
                    \Mail::send(array(),
                array(
                   'name' => $userName,
                   'email' => $frm,
                ), function($message) use ($content,$to,$frm)
               {
               $message->setBody($content, 'text/html');
               $message->from($frm);
               $message->to($to, 'Admin')
                ->subject('PIN ');
              });
              //$response["ok"] = true;
  }
  public function refresh(Request $request) {
      	$duration=10;
        if($request->header('Authorization')== null)
              return response()->json(['status_code' => '404']);
        try{
        $tokenEncoded = new TokenEncoded($request->header('Authorization'));
        }
        catch(EmptyTokenException $e){
        }
        $publicKey = file_get_contents('/home/tanta/joblaravelTest.tanta.club/rsa.public');
        try {
            $tokenEncoded->validate($publicKey, JWT::ALGORITHM_RS256);
        } catch (IntegrityViolationException $e) {
        } catch (Exception $e) {
        }
        $tokenDecoded = $tokenEncoded->decode();
        $payloadable = $tokenDecoded->getPayload();
        if(time()>$payload['iat']){
              return response()->json(['status_code' => '404']);
        
        $user=usrs::where([['email','=',$payloadable['email']]])->first();
        $user=collect($user);
        $user = $user->except('PIN');
        $payloadable['iat']=time()+$duration*60;
        $tokenDecoded = new TokenDecoded([], $payloadable);
        $privateKey =file_get_contents('/home/tanta/joblaravelTest.tanta.club/rsa.private');
        $tokenEncoded = $tokenDecoded->encode($privateKey, JWT::ALGORITHM_RS256)->__toString();
        return response()->json(compact('tokenEncoded','duration','user'));
  }
}
}