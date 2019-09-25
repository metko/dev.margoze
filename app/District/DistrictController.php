<?php

namespace App\District;

use App\Http\Controllers\Controller;

class DistrictController extends Controller
{
    public function get($commune_id)
    {
        return District::where('commune_id', $commune_id)->get();
    }
}
