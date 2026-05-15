<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class FormController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('contact._form',compact('categories'));
    }

    public function store(Request $requests){
        
    }
}
