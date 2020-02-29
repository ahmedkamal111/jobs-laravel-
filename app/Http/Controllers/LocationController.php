<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Location;
class LocationController extends Controller
{
    public function showAll()
    {
        return Location::all();
    }
}
