<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    //
    public $guarded = [];
    public $timestamps = false;
    protected $table = "jobs";
    protected $primaryKey = "id";
    
    public function type() {
        return $this->belongsTo('App\jobType');
    }
    public function candidates() {
        return $this->hasMany("App\Candidate");
    }
}
