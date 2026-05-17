<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Contact;

class AdminController extends Controller
{
    public function index(Request $request){
        $categories = Category::all();
        $tags = Tag::all();
        $query = Contact::query();  
      
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('last_name', 'like', "%{$keyword}%")
                ->orWhere('first_name', 'like', "%{$keyword}%")
                ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', (int)$request->category_id);
        }

        if ($request->has('gender') && (int)$request->gender !== 0) {
            $query->where('gender', (int)$request->gender);
        }

        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->date);
        }

        $contacts = $query->paginate(8); 
        return view('admin.index',compact('categories','tags','contacts'));
    }

    public function show(Contact $contact){
        return view('admin.show',compact('contact'));
    }

    public function edit(Tag $tag){
        return view('admin.tags.edit',compact('tag'));
    }

    public function store(TagRequest $request){
        $validated = $request->validated();
        Tag::create($validated);
        return redirect('/admin');
    }

    public function update(Tag $tag,TagRequest $request){
        $tag->update($request->validated());
        return redirect('/admin');
    }

    public function destroy(Tag $tag, Contact $contact){
        $tag->delete();
        $contact->delete();
        return redirect('/admin');
    }

}
