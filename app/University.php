<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    //
    protected $table = "univ";
    public function candidates() {
        return $this->hasMany("App\Candidate", "id");
    }

}
