<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Privileges;
use App\Profiles;
use App\usrs;
class UserController extends Controller
{
    public function showPrivlage(Request $request)
    {
      //$request['userId']=2;
      $userId= $request->input('userId');
      $priv=usrs::select('priv')->where('usr_id','=',$userId)->value('priv');
      return Privileges::where('id','<',$priv)->get();
    }

    public function showProfile()
    {
      return Profiles::All();
    }
    
    
    public function storeuser(Request $request)
        {
            // $request->merge([
                 
            //          'CID'=> 1,
            //          'Name' =>'alaa qqbakr',
            //          'uprofile' => 2,
            //          'priv'=> 4,
            //          'email'=>'alaa.abobakr1994@gmail.com',
            //          'mobile'=>'01125546265' ,
            //          'userId'=>7
            //      ]);
             $userId= $request->input('userId');
             
             $priv=usrs::select('priv')->where('usr_id','=',$userId)->value('priv');
             if($priv <= $request->input("priv") )
             return "error";
                 
            $validateData=$request->validate( [
                'uprofile' => 'required',
                'priv'=>'required',
                'email'=>'required',
                'Name' => 'required',
                'mobile'=>'required'
    
            ]);
             
             
            $CID=$request->input("CID");     
            $userName = $request->input("Name");
            $uprofile= $request->input("uprofile");
            $priv=$request->input("priv");
            $email=$request->input("email");
            $mobile=$request->input("mobile");
    
           
            $usrs = new usrs();
            $maxID = $usrs::max('usr_id');
            $usrs->CID = $CID;
            $usrs->Name = $userName;
            $usrs->email = $email;
            $usrs->mobile = $mobile;
            $usrs->priv = $priv;
            $usrs->uprofile = $uprofile;
            $usrs->usr_id=$maxID+1;
            $usrs->save();
            
            
        
        }


    public function updateuser(Request $request)
        { 
            // $request['CID']=1;
            // $request['uprofile']=1;
            // $request['priv']=1;
            // $request['email']=1;
            // $request['Name']=1;
            // $request['mobile']=1;
            // $request['updateUserId']=7;

            // check if news exists
            $user_id= $request->input("updateUserId");
            $usrs = usrs ::find($user_id);
            $validateData=$request->validate( [
               
               'uprofile' => 'required',
                'priv'=>'required',
                'email'=>'required',
                'Name' => 'required',
                'mobile'=>'required'
                
            ]);
             
            $uprofile = $request->input("uprofile");
            $priv = $request->input("priv");
            $email = $request->input("email");
            $Name = $request->input('Name');
            $mobile = $request->input("mobile");
           
            $usrs->priv = $priv;
            $usrs->email = $email;
            $usrs->Name = $Name;
            $usrs->mobile = $mobile;
         
            $usrs->save();
            //return 1;
        }
        
    public function showuser(Request $request)
        {
             $company_id=$request->input('CID');
             return usrs::where('CID' ,$company_id)->get();
        }
        
    public function deleteuser(Request $request)
        {
           // $request['user_id']=6;
            $user_id=$request->input('user_id');
             $usrs = usrs::find($user_id);
             $usrs->delete();
        }
    
}
