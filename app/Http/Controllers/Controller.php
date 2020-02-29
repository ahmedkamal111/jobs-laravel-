<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function tokenCheck(Request $request){
        /*$id=$request->input('id');
        $sendToken=$request->input('api_token');
        $actualToken=User::select('api_token')->where('id',$id)->value('api_token');
        $sendToken=Crypt::decryptString($sendToken);
        if($sendToken!=$actualToken){
            return "0";
        }*/
    }
}
