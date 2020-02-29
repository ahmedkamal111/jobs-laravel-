<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\University;
class UniversityController extends Controller
{
    public function showAll()
    {
        return University::all();
    }
}
