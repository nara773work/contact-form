<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactController extends Controller
{
    public function index()
    {

        $categories = Category::all();
        $tags = Tag::all();

        return view('contact.index', compact('categories', 'tags'));
    }

    public function confirm(ContactRequest $request)
    {
        $validated = $request->validated();
        $category = Category::find($validated['category_id']);
        $tags = collect();

        if ($request->has('tags')) {
            $tags = Tag::whereIn('id', $request->tags)->get();
        }

        return view('contact.confirm', compact('validated', 'category', 'tags'));
    }

    public function store(ContactRequest $request)
    {
        $validated = $request->validated();

        $contact = Contact::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'building' => $validated['building'],
            'tel' => $validated['tel'],
            'category_id' => $validated['category_id'],
            'detail' => $validated['detail'],
        ]);

        if (! empty($validated['tag_ids'])) {
            $contact->tags()->attach($validated['tag_ids']);
        }

        return view('contact.thanks');
    }

    public function thanks()
    {
        return view('contact.thanks');
    }
}
