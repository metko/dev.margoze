<?php

namespace App\Home;

use App\Category\Category;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //laraflash()->mwessage()->content('Félicitation vous êtes bien inscrit')->title('Yeah!')->type('success');
        $categories = Category::all();

        return view('welcome', compact('categories'));
    }
}
