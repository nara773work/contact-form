<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Tag;
use App\Models\Category;
use App\Models\Contact;

class ContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected $seed = true;

    public function test_can_submmit(): void
    {
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

        $response = $this->post('/contact/store', $data);

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
