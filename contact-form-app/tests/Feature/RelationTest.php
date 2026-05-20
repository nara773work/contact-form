<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_contact(): void
    {
        $this->seed();
        $category = Category::first();
        $contacts = Contact::factory()->create(['category_id' => $category->id]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contacts->id,
            'category_id' => $category->id,
        ]);
        $this->assertEquals($contacts->category_id, $category->id);
    }

    public function test_contacts_belongs_to_categoriy(): void
    {
        $this->seed();

        $category = Category::first();
        $contacts = Contact::factory()->create(['category_id' => $category->id]);
        $tags = Tag::take(3)->get();

        $contacts->tags()->sync($tags->pluck('id')->toArray());
        $this->assertTrue($contacts->category->is($category));
        $this->assertCount(3, $contacts->tags);
    }

    public function test_middle_table_tags_contacts(): void
    {
        $this->seed();
        $contacts = Contact::factory()->count(3)->create();
        $tags = Tag::first();

        $tags->contacts()->sync($contacts->pluck('id')->toArray());

        foreach ($contacts as $contact) {
            $this->assertDatabaseHas('contact_tag', [
                'contact_id' => $contact->id,
                'tag_id' => $tags->id,
            ]);
        }
    }
}
