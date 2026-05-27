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
        //正常系
        $contacts = collect([
            [
                'gender' => 9, //不正な性別値
                'email'  =>'test@co.com',
                'date'   => '2025.06.12'
            ],
            [
                'gender' => 3,
                'email'  => 'abc@co.com',
                'date'   =>'2025.06.12'
            ]
        ]);
        
        //gender = 3 で検索する hit1

        $result_gender = $contacts->where('gender', 3);

        $this->assertContains($contacts[1], $result_gender ); 
        $this->assertNotContains($contacts[0], $result_gender );
        $this->assertCount(1, $result_gender);


        //email = noemail@co.comで検索する hit0
        $result_email = $contacts->where('email', 'noemail@co.com');

        $this->assertNotContains($contacts[0], $result_email ); 
        $this->assertNotContains($contacts[1], $result_email);
        $this->assertCount(0, $result_email);


        //date = 2025.06.12 で検索する hit2
        $result_date = $contacts->where('date', '2025.06.12');

        $this->assertContains($contacts[0], $result_date ); 
        $this->assertContains($contacts[1], $result_date);
        $this->assertCount(2, $result_date);

        
        //不正な性別値を拒否する
        $incorrect_gender = $contacts->filter(function(array $item,$key){
           return 0 < $item['gender'] && $item['gender'] < 3;
        });

        $this->assertNotContains($contacts[0], $incorrect_gender);

    }
}
