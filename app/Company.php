<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CompanyJob;

class Company extends Model
{
    //
    protected $table = "companies";
    public $timestamps = false;
    protected $primaryKey = "cid";

    public function CompanyJob()
    {
        //return $this->hasOne(CompanyJob::class);
        return $this->hasOne('App\CompanyJob','cid','cid');
    }

}
