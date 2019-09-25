<?php

namespace App\Commune;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommuneController extends Controller
{
    public function index(Request $request)
    {
        $communes = Commune::all();

        if ($request->ajax()) {
            return response()->json($communes);
        }
    }
}
