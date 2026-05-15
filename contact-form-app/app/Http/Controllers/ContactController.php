<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;

class ContactController extends Controller
{
    public function index(){
        $categories = Category::all();
        $tags = Tag::all(); 

        return view('contact.index', compact('categories', 'tags'));
    }
    
    public function confirm(Request $request){

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

    if ($request->has('tag_ids')) {
        $tag_ids = Tag::whereIn('id', $request->tag_ids)->get();
    }
        return view('contact.confirm',compact('validated','category','tags'));
    }

    public function thanks(Request $request){


    if ($request->has('tag_ids')) {
        $contact->tags()->attach($request->tag_ids);
    }
        return view('contact.thanks');
    }

   
}
