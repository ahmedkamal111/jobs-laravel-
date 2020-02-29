<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Company;

class CompanyJob extends Model
{
    //
    protected $table = "companies_jobs";
    public $timestamps = false;
    protected $primaryKey = "cid";
    public $fillable = ['cid','jslogan','jwelcome'];
    public function Company()
    {
        //return $this->hasOne(Company::class,"CID");
            return $this->belongsTo('App\Company', 'cid','cid');
    }
}
