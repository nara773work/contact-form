<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Contact;

class ContactValidateTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_contact_index_validate(): void
    {
        // 正常系データ（1つ目は不正な性別値を含む）
        $contacts = collect([
            [
                'gender' => 9, // 不正な性別値
                'email'  => 'test@co.com',
                'date'   => '2025.06.12'
            ],
            [
                'gender' => 3,
                'email'  => 'abc@co.com',
                'date'   => '2025.06.12'
            ]
        ]);
        
        //  gender = 3 で検索する hit 1
        $result_gender = $contacts->where('gender', 3);

        $this->assertContains($contacts[1], $result_gender); 
        $this->assertNotContains($contacts[0], $result_gender);
        $this->assertCount(1, $result_gender);


        // email = noemail@co.com で検索する hit 0
        $result_email = $contacts->where('email', 'noemail@co.com');

        $this->assertNotContains($contacts[0], $result_email); 
        $this->assertNotContains($contacts[1], $result_email);
        $this->assertCount(0, $result_email);


        // date = 2025.06.12 で検索する hit 2
        $result_date = $contacts->where('date', '2025.06.12');

        $this->assertContains($contacts[0], $result_date); 
        $this->assertContains($contacts[1], $result_date);
        $this->assertCount(2, $result_date);


        // 不正な性別値を拒否
        $filtered_contacts = $contacts->filter(function($item) {
            return 1 <= $item['gender'] && $item['gender'] <= 3;
        });

        $this->assertNotContains($contacts[0], $filtered_contacts);
        
    }

    public function test_contact_save_validate(): void{
        $contact = collect([[
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
        ],
        [
            'first_name' => 'test',
            'last_name' => 'name',
            'gender' => 3,
            'email' => 'test@co.com',
            'tel' => '0001234123456',
            'address' => 'test',
            'building' => '',
            'category_id' => 3,
            'tag_ids' => 3,
            'detail' => 'detail',
        ]
        ]);

        $this->assertNotNull($contact[0]['first_name']);
        $this->assertNotNull($contact[0]['last_name']);
        $this->assertNotNull($contact[0]['gender']);
        $this->assertNotNull($contact[0]['email']);
        $this->assertNotNull($contact[0]['tel']);
        $this->assertNotNull($contact[0]['address']);
        $this->assertNotNull($contact[0]['building']);
        $this->assertNotNull($contact[0]['tag_ids']);
        $this->assertNotNull($contact[0]['detail']);

        $this->assertNotNull($contact[1]['first_name']);
        $this->assertNotNull($contact[1]['last_name']);
        $this->assertNotNull($contact[1]['gender']);
        $this->assertNotNull($contact[1]['email']);
        $this->assertNotNull($contact[1]['tel']);
        $this->assertNotNull($contact[1]['address']);
        $this->assertNotNull($contact[1]['building']);
        $this->assertNotNull($contact[1]['tag_ids']);
        $this->assertNotNull($contact[1]['detail']);

        $filtered_contact = $contact->filter(function($item) {
            $length = mb_strlen($item['tel']);
            return $length == 10 && $length == 11;
        });

        $this -> assertNotContains($contact[1],$filtered_contact);
    }
}