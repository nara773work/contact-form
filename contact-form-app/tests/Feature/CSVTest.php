<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Contact;

class CSVTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    protected $seed = true;

    public function test_no_serach_export(): void
    {
        $user = User::first();
        $categories = Category::all();
        $tags = Tag::all();
        $query = Contact::all();

        $response = $this->actingAs($user)->get('/contacts/export');
        $response->assertStatus(200);
        $csvContent = mb_convert_encoding($response->getContent(), 'UTF-8', 'SJIS-win');

        $this->assertStringContainsString('id,氏名,性別,電話,住所,建物,カテゴリ,作成日時', $csvContent);
    }

    public function test_serach_export(): void{
        $user = User::first();
        $categories = Category::all();
        $tags = Tag::all();
        $query = Contact::all();

        $response = $this->actingAs($user)->get('/contacts/export?gender=2');
        $response->assertStatus(200);

        $gender = mb_convert_encoding($response->getContent(), 'UTF-8', 'SJIS-win');
        $this->assertStringContainsString('女性', $gender);
        $this->assertStringNotContainsString('男性', $gender);

        $response = $this->actingAs($user)->get('/contacts/export?category_id=4');
        $response->assertStatus(200);

        $csvContent = mb_convert_encoding($response->getContent(), 'UTF-8', 'SJIS-win');

        $this->assertStringContainsString('id,氏名,性別,電話,住所,建物,カテゴリ,作成日時', $csvContent);
    }
}
