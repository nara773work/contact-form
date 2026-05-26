<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;

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

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('last_name', $keyword)
                    ->orWhere('first_name', $keyword)
                    ->orwhere('email', $keyword);
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('date')) {
            $query->where('created_at', $request->date);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
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
        $contact = Contact::with('tags')->findOrFail($id);

        return response()->json([
            'data' => $contact,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, string $id)
    {
        $contact = Contact::findOrfail($id);
        $contact->tags()->sync($request->input('tag_ids', []));
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
