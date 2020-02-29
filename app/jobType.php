<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobType extends Model
{
    //
    protected $table = "job_types";
    protected $primaryKey = "id";
    public function jobs() {
        return $this->hasMany("App\Job");
    }
}
