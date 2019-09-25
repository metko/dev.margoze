<?php

namespace App\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json($categories);
        }
    }
}
