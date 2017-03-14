<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Shop\Models\Category;

class CategoriesController extends Controller
{
    /**
     * Display the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('laralum_shop::index', ['categories' => Category::all()]);
    }
}
