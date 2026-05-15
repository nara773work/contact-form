<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;

class IndexController extends Controller
{
    public function index(){
        $categories = Category::all();
        $tags = Tag::all(); 

        return view('contact.index', compact('categories', 'tags'));
    }
    
    public function store(Request $request){

    $validated = $request->validate([
    'first_name' => 'required',
    'last_name'  => 'required',
    'email'      => 'required',
    'tel'        => 'required',
    'address'    => 'nullable',
    'building'   => 'nullable',
    'detail'     => 'required',
    'gender'     => 'required',
    'category_id' => 'required'
]);

    $category = Category::find($validated['category_id']);
    $tags = collect();

    if ($request->has('tag')) {
        $tag_ids = Tag::whereIn('tad_id', $request->tag_id)->get();
    }
        return view('contact.confirm',compact('validated','category','tag_ids'));
    }

   
}
