<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AdminRequest $request)
    {

        $perPage = $request->is('api/*')
        ? ($request->per_page ?? 20)
        : ($request->per_page ?? 7);

        $query = Contact::query();

        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', "%{$request->first_name}%");
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return $query->paginate($perPage);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        $validated = $request->validated();

        $contact = Contact::create($validated);
        $contact->tags()->attach($validated['tag_ids'] ?? []);

        $contact->load(['category', 'tags']);

        // ★ Resource でラップして返す
        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Contact::findOrFail($id);

        return response()->json([
            'data' => $contact,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contact = Contact::findOrfail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|integer|in:1,2,3',
            'email' => 'required|string|email|max:255',
            'tel' => 'required|string|regex:/^[0-9]{10,11}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'detail' => 'required|string|max:120',
        ]);

        $contact->tags()->sync($request->input('tag_ids', []));

        return response()->json([
            'message' => 'お問い合わせを更新しました',
            'data' => $contact,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrfail($id);
        $contact->delete();

        return response()->json(null, 204);
    }
}
