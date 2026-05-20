<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    // 画面アクセス　ページ表示
    public function test_view_input_page(): void
    {
        // Arrange
        $this->seed();
        $categories = Category::all();
        $tags = Tag::all();

        // Act
        // (/)が正常に表示される
        $response = $this->get(route('contact.index'));
        $response->assertStatus(200);

        // categories・tagsが変数として渡される、
        // カテゴリー名とタグ名がページに表示される
        $response->assertViewHas('categories');
        $response->assertViewHas('tags');
        $response->assertSee((string) $categories->get(1)->id);
        $response->assertSee((string) $tags->get(1)->id);

        // thanksページが正常に表示される
        $response = $this->get(route('contact.thanks'));
        $response->assertStatus(200);
    }

    // 画面アクセス　管理画面アクセス制御_1　認証済みユーザーのみダッシュボードを表示
    public function test_can_view_admin_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.index'));
        $response->assertStatus(200);
    }

    // 画面アクセス　管理画面アクセス制御_2　未認証ユーザーはリダイレクト
    public function test_can_not_admin_redirect(): void
    {
        $response = $this->get(route('admin.index'));
        $response->assertRedirect(route('login'));
    }

    // お問い合わせフォーム確認ページ表示_1　バリデーション通過時は確認ページが表示される、
    // 入力内容が表示される
    public function test_view_confirm_page(): void
    {
        // Arrange
        $category = Category::create(['id' => 1, 'content' => 'テストカテゴリ']);
        $contact = Contact::factory()->make(['category_id' => $category->id])->toArray();

        // Act
        $response = $this->post(route('contact.confirm'), $contact);

        $response->assertStatus(200);
        $response->assertSee($contact['first_name']);
    }

    // お問い合わせフォーム確認ページ表示_2 バリデーションエラーを表示する
    public function test_return_error(): void
    {
        // Arrange
        $category = Category::create(['id' => 1, 'content' => 'テストカテゴリ']);
        $contact = Contact::factory()->make(['category_id' => $category->id])->toArray();

        // Act
        $response = $this->post(route('contact.confirm'), [
            'detail' => '',
        ]);

        $response->assertSessionHasErrors('detail');
        $response = $this->get(route('contact.index'));
    }

    // お問い合わせ送信
    public function test_submmit_contact(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::create(['id' => 1, 'content' => 'テストカテゴリ']);

        $newcontact = Contact::factory()->create([
            'first_name' => 'Test',
            'category_id' => 1,
        ])->toArray();

        $response = $this->actingAs($user)->post(route('contact.store'), $newcontact);
        $this->assertDatabaseHas('contacts', ['first_name' => 'Test']);
    }

    // 検索フィルターとページネーション_1 検索フィルター
    public function test_search(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::create(['id' => 1, 'content' => 'テストカテゴリ']);

        Contact::factory()->create([
            'first_name' => 'Test',
            'category_id' => 1,
        ]);
        Contact::factory()->create([
            'first_name' => 'Other',
            'category_id' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('admin.index', [
            'first_name' => 'Test',
            'category_id' => 1,
        ]));

        // Act
        $response->assertSee('Test');
    }

    // 検索フィルターとページネーション_2 ページネーション
    public function test_pagination(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::create([
            'id' => 1,
            'content' => 'テストカテゴリ',
        ]);
        Contact::factory()->count(15)->create([
            'category_id' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('admin.index', ['page' => 2]));

        $response->assertStatus(200);

        $secondPage = Contact::orderBy('id')->skip(7)->first();
        $response->assertSee($secondPage->first_name);
    }

    // お問い合わせ詳細
    public function test_detail_admin_show(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::create([
            'id' => 1,
            'content' => 'テストカテゴリ',
        ]);
        $contact = Contact::factory()->create([
            'category_id' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('admin.show', $contact->id));

        $response->assertStatus(200);
    }

    // お問い合わせ削除
    public function test_detail_admin_delete(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = Category::create([
            'id' => 1,
            'content' => 'テストカテゴリ',
        ]);
        $contact = Contact::factory()->create([
            'category_id' => 1,
        ]);

        $response = $this->actingAs($user)->delete(route('admin.contacts.delete', $contact->id));
        $response->assertRedirect(route('admin.index'));
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    // お問い合わせ削除
    public function test_tags_crud(): void
    {
        // Arrange
        $user = User::factory()->create();
        $tag = Tag::create([
            'id' => 1,
            'name' => 'テストタグ',
        ]);

        $newtag = ['name' => 'new tag'];
        $response = $this->actingAs($user)->get(route('admin.edit', $tag->id));
        $response = $this->actingAs($user)->post(route('admin.edit.post'), $newtag);
        $this->assertDatabaseHas('tags', ['name' => 'new tag']);

        $puttag = ['name' => 'put tag'];
        $response = $this->actingAs($user)->put(route('admin.put', $tag->id), $puttag);
        $this->assertDatabaseHas('tags', ['name' => 'put tag']);

        $response = $this->actingAs($user)->delete(route('admin.tags.delete', $tag->id));
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);

        $response->assertRedirect(route('admin.index'));
    }
}
