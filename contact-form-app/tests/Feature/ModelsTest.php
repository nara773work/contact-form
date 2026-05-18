<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Contact;

class ModelsTest extends TestCase
{
    use RefreshDatabase;
    public function test_category_relation(): void
    {
        $this->seed(); 
        $categories = Category::first();
        $contact = Contact::factory()->create(['category_id' => $categories->id]);

        $this->assertTrue($categories->contacts->contains($contact));
    }
    public function test_contact_relation(): void
    {
        $this->seed(); 
        $category = Category::first();
        $contact = Contact::factory()->create([
        'category_id' => $category->id,
    ]);

        $this->assertTrue($contact->category->is($category));
    }
}
