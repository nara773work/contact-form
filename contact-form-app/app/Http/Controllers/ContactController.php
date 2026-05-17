<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index(){
        $categories = Category::all();
        $tags = Tag::all(); 

        return view('contact.index', compact('categories', 'tags'));
    }
    
    public function confirm(ContactRequest $request){
    
        $validated = $request->validated();

        $category = Category::find($validated['category_id']);
        $tags = collect();

        if ($request->has('tag_ids')) {
            $tag_ids = Tag::whereIn('id', $request->tag_ids)->get();
    }
        return view('contact.confirm',compact('validated','category','tags'));
    }

    public function thanks(ContactRequest $request){
        $validated = $request->validated();

        $contact = Contact::create([
            'first_name'  => $validated['first_name'],
            'last_name'   => $validated['last_name'],
            'email'       => $validated['email'],
            'address'     => $validated['address'],
            'tel'         => $validated['tel'],
            'category_id' => $validated['category_id'],
            'detail'      => $validated['detail'],
        ]);

    if ($request->has('tag_ids')) {
        $contact->tags()->attach($request->tag_ids);
    }
        return view('contact.thanks');
    }

   
}
