<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;
use Tests\TestCase;

class APITest extends TestCase
{
     
    use RefreshDatabase;
    public function test_view_contact_list()
    {
        $this->seed(); 
        $categories = Category::all();
        $tags = Tag::all();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200);
    }

    public function test_create(): void
    {
        $this->seed(); 
        $categories = Category::first();
        $tags = Tag::all();

        $response = $this->postJson('/api/contacts', [
            'first_name'  => 'Test',
            'last_name'   => 'User',
            'gender'      => 1,
            'email'       => 'test@example.com',
            'tel'         => '00011112222',
            'address'     => 'address',
            'building'    => '',
            'category_id' => $categories->id,
            'detail'      => 'detail',
            'tag_ids'     => $tags->pluck('id')->toArray(),
        ]);

        $response->assertStatus(201);
    }
    public function test_view_show(){
        $this->seed(); 
        $categories = Category::first();
        $tags = Tag::all();

        $contact = Contact::factory()->create();
        $response = $this->getJson("/api/contacts/{$contact->id}");
        $response->assertStatus(200);
    }
    public function test_update()
    {
        $this->seed(); 
        $categories = Category::first();
        $tags = Tag::all();
        $contact = Contact::factory()->create();

        $response = $this->putJson("/api/contacts/{$contact->id}", [
            'first_name'  => 'update Test',
            'last_name'   => 'update User',
            'gender'      => 1,
            'email'       => 'test@example.com',
            'tel'         => '00011112222',
            'address'     => 'address',
            'building'    => '',
            'category_id' => $categories->id,
            'detail'      => 'update test'
        ]);

        $response->assertStatus(200);
    }
     public function test_delete()
    {
        $this->seed(); 
    
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(204);
    
}
}