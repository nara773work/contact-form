<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Contact;

class AdminController extends Controller
{
    public function index(){
        $categories = Category::all();
        $tags = Tag::all();
        $contacts = Contact::paginate(7); 
        return view('admin.index',compact('categories','tags','contacts'));
    }

    public function show(Contact $contact){
        return view('admin.show',compact('contact'));
    }

    public function edit(Tag $tag){
        return view('admin.tags.edit',compact('tag'));
    }

    public function store(Tag $tag){

    }

    public function update(Tag $tag){

    }

    public function destroy(Tag $tag){
        $tag->delete();
        return redirect('/admin');
    }

}
