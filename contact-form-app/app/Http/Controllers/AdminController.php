<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\TagRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class AdminController extends Controller
{
    public function index(AdminRequest $request)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $query = Contact::query();

        $validated = $request->validated();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('last_name', $keyword)
                    ->orWhere('first_name', $keyword)
                    ->orwhere('email', $keyword);
            });
        }

        if ($request->has('gender') && (int) $request->gender !== 0) {
            $query->where('gender', (int) $request->gender);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate(7);

        return view('admin.index', compact('categories', 'tags', 'contacts'));

        return csv('admin.index', compact('categories', 'tags', 'contacts'));
    }

    public function show(Contact $contact)
    {
        return view('admin.show', compact('contact'));
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function store(TagRequest $request)
    {
        $validated = $request->validated();
        Tag::create($validated);

        return redirect('/admin');
    }

    public function update(Tag $tag, TagRequest $request)
    {
        $tag->update($request->validated());

        return redirect('/admin');
    }

    public function destroyTag(Tag $tag)
    {
        $tag->delete();

        return redirect('/admin');
    }

    public function destroyContact(Contact $contact)
    {
        $contact->delete();

        return redirect('/admin');
    }

    public function export(Request $request){
        $categories = Category::all();
        $tags = Tag::all();

        $query = Contact::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('last_name', $keyword)
                    ->orWhere('first_name', $keyword)
                    ->orwhere('email', $keyword);
            });
        }

        if ($request->has('gender') && (int) $request->gender !== 0) {
            $query->where('gender', (int) $request->gender);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $contacts = $query->orderBy('created_at', 'desc')->get();

        $column = "id,氏名,性別,電話,住所,建物,カテゴリ,作成日時\r\n";

        foreach ($contacts as $contact) {
            if($contact->gender == 1){
                $gender = '男性';
            }
            elseif($contact->gender == 2){
                $gender = '女性';
            }
            else{
                $gender = 'その他';
            }
            $Name = "{$contact->first_name} {$contact->last_name}";

            $category = $contact -> category -> content;

            $column .= "{$contact->id},{$Name},{$gender},{$contact->tel},{$contact->address},{$contact->building},{$category},{$contact->created_at}\r\n";
        }

        $column = mb_convert_encoding($column, 'SJIS-win', 'UTF-8');

        return response($column)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="contacts.csv"');

    }

}
