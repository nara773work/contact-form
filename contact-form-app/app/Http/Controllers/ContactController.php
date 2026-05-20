<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Contact;
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

        if ($request->has('tags')) {
            $tags = Tag::whereIn('id', $request->tags)->get();
        }
        return view('contact.confirm',compact('validated','category','tags'));
    }

    public function store(ContactRequest $request){
        $validated = $request->validated();

        $contact = Contact::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender'    => $validated['gender'],
            'email'     => $validated['email'],
            'address'   => $validated['address'],
            'building'  => $validated['building'],
            'tel'       => $validated['tel'],
            'category_id' => $validated['category_id'] ,
            'detail'    => $validated['detail'],
        ]);
        return view('contact.thanks');
    }

    public function thanks(){
        return view('contact.thanks');
        }
}
