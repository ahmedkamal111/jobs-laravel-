<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Job;
class Candidate extends Model
{
    public $timestamps = false;
    protected $table = "candi";
    protected $primaryKey = "id";
    public $fillable = [
'id',
'CID',
'Dateofbirth',
'email',
'Gender',
'JobType',
'LinkedIn',
'Location',
'Mobile',
'name',
'Onlinecv',
'salary',
'university',
'jobId',
'cv_file'
];
    public function getJob($ids) {
        return Job::find($ids);
    }
    public function location() {
        return $this->belongsTo("App\Location", "Loc");
    }
        public function university() {
        return $this->belongsTo("App\University","university");
    }

}
