<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Gender;
class GenderController extends Controller
{
    public function showAll()
    {
        return Gender::all();
    }
}
