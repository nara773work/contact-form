<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;


class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    protected $seed = true;

    public function test_serach_filter(): void
    {
        $user = User::first();

        $keyword_first_name = [
            'first_name' => '春香'
        ];

        $keyword_last_name = [
            'last_name' => '小林'
        ];

        $keyword_email = [
            'email' => 'naoko35@yahoo.co.jp'
        ];

        $gender = [
            'gender' => 2
        ];

        $category_id = [
            'category_id' => 3
        ];

        $date = [
            'created_at' => '2026-05-28' 
        ];

        $response = $this->actingAs($user)->get('admin/?first_name=春香');
        $response->assertok();
        $response = $this->actingAs($user)->get('admin/?last_name=小林');
        $response->assertok();
        $response = $this->actingAs($user)->get('admin/?email=naoko35@yahoo.co.jp');
        $response->assertok();
        $response = $this->actingAs($user)->get('admin/?gender=2');
        $response->assertok();
        $response = $this->actingAs($user)->get('admin/?category_id=3');
        $response->assertok();
        $response = $this->actingAs($user)->get('admin/?created_at=2026-05-28');
        $response->assertok();

        $response = $this->actingAs($user)->get('admin/?first_name=ppp');
        $this->assertDatabaseMissing('contacts', ['first_name'=>'ppp']);
        $response->assertok(); 
    }

    public function test_pagenate(): void
    {
        $user = User::first();
        $contacts = Contact::all();

        $response = $this->actingAs($user)->get('/admin');
        $page1 = $response->viewData('contacts');
        $this->assertCount(7, $page1);
    }

    public function test_view_admin_show(): void{

        $user = User::first();
        $contact = Contact::first();
        $category = Category::all();

        $response = $this->actingAs($user)->get("/admin/contacts/{$contact->id}");

        $response->assertSee([
            $contact->first_name,
            $contact->last_name,
            $contact->email,
            $contact->tel,
            $contact->address,
            $contact->building,
            $contact->category->content,
            $contact->detail,
        ]);
    }

    public function test_index_confirm_store_thanks(): void{
        $tags = Tag::all();
        $categories = Category::all();

        //入力画面の表示
        $response = $this->get('/contact');
        $response->assertViewHas('categories');
        $response->assertViewHas('tags');
        foreach ($categories as $category) {
            $response->assertSee($category->content);
        }
        foreach ($tags as $tag) {
            $response->assertSee($tag->name);
        }
        $response->assertOk();
        
        $data = ([
            'first_name' => 'Test',
            'last_name' => 'Name',
            'gender' => 1,
            'email' => 'Test@co.com',
            'tel' => '00012341234',
            'address' => 'Test',
            'building' => '',
            'category_id' => 4,
            'tag_ids' => [1,2],
            'detail' => 'detail',
        ]);

        //確認画面の表示
        $response = $this->post('/contact/confirm',$data);
        $response->assertOk();

        //保存されているか確認 中間テーブルに紐づいているかも確認
        $response = $this->post('/contact/store',$data);
        $this->assertDatabaseHas('contacts', [
            'first_name' => 'Test',
            'last_name' => 'Name',
            'gender' => 1,
            'email' => 'Test@co.com',
            'tel' => '00012341234',
            'address' => 'Test',
            'building' => null,
            'category_id' => 4,
            'detail' => 'detail',
        ]);

        $contact = Contact::where('first_name', 'Test')->first();

        $this->assertDatabaseHas('contact_tag',[
            'contact_id' => $contact->id,
            'tag_id' => 1
        ]);

        $this->assertDatabaseHas('contact_tag',[
            'contact_id' => $contact->id,
            'tag_id' => 2
        ]);

        $response->assertOk();        
    }
}
