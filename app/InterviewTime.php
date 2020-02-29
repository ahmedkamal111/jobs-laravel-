<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterviewTime extends Model
{
    //
    
    protected $table ='inter_time';
    public $timestamps=false;
   // public $primaryKey ='id';
 //elquont will assume that each table has a primarkey column named ID
    public $fillable=['cid','job_id','date','time','duration','candi_id','regist_time'];
    
}
