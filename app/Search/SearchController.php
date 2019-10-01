<?php

namespace App\Search;

use App\Demand\Demand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            // $demand = Demand::search($request->get('search'))->get();
            $demands = Demand::search($request->get('search'))->query(function ($builder) {
                $builder->with('owner', 'sector', 'commune', 'district', 'category');
            })->get();

            return response()->json($demands);
        }
    }
}
