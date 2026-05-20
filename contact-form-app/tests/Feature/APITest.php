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

        //JSON形式で一覧が返る
        $response = $this->getJson('/api/contacts');
        $response->assertStatus(200);

        //ページネーションが機能している
        $response = $this->get('/api/contacts?page=21');
        $response->assertStatus(200);

        //検索が機能している
        Contact::factory()->create([
        'first_name' => 'Test',
        'category_id' => 1,
        ]);
        Contact::factory()->create([
        'first_name' => 'Other',
        'category_id' => 1,
        ]);
        
        $response = $this->getJson('/api/contacts?first_name=Test&category_id=1');
        $response->assertSee('Test');
        
        //バリデーションエラー
        $response = $this->postJson('/api/contacts', [
        'first_name' => ''
        ]);
        $response->assertStatus(422);

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

        //バリデーションエラー
        $response = $this->postJson('/api/contacts', [
        'first_name' => ''
        ]);
        $response->assertStatus(422);        
    }
    public function test_view_show(){
        $this->seed(); 
        $categories = Category::first();
        $tags = Tag::all();

        $contact = Contact::factory()->create();
        $response = $this->getJson("/api/contacts/{$contact->id}");
        $response->assertStatus(200);

        $response = $this->getJson("/api/contacts/0");
        $response->assertStatus(404);
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

        $response = $this->getJson("/api/contacts/0");
        $response->assertStatus(404);


        //バリデーションエラー
        $response = $this->putJson("/api/contacts/{$contact->id}", [
        'first_name' => ''
        ]);
        $response->assertStatus(422);
    }
     public function test_delete()
    {
        $this->seed(); 
    
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(204);

        $response = $this->getJson("/api/contacts/{$contact->id}");
        $response->assertStatus(404);
}
}