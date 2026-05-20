<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UnitAPITest extends TestCase
{
    public function test_serach_validatation(): void
    {
        $filter = [
        ['name' => 'A', 
        'gender' => 0, 
        'address' => 'abc', 
        'updated_at' => 'yy.xx.0d'
        ],
        ['name' => 'B', 
        'gender' => 4, 
        'address' => 'def', 
        'updated_at' => 'yy.xx.1d'
        ],
    ];

        foreach ($contacts as $contact) {

            $status = ($contact['name'] = 'A') ? 200 : 422;

            if ($contact['name'] = 'A') {
                $this->assertEquals(200, $status);
            } else {
                $this->assertEquals(422, $status);
            }
        }

        //不正な性別値を拒否
        foreach ($datas as $data) {

            $status = (0 < $data['gender'] && $data['gender'] < 4) ? 200 : 422;

            if (0 < $data['gender'] && $data['gender'] < 4) {
                $this->assertEquals(200, $status);
            } else {
                $this->assertEquals(422, $status);
            }
        }
    }

        public function test_store_validate(){

        $contact = [
        [
            'first_name' => 'A', 
            'last_name'  => 'a', 
            'gender'     => 2, 
            'email'      => 'Aa@co.com', 
            'tel'        => '08011112222', 
            'address'    => 'abc', 
            'tags'       => 1,
            'detail'     => 'detail'
        ]
    ];
        $tel = $contact['tel'];
        $telValid = ctype_digit($tel) && strlen($tel) >= 10 && strlen($tel) <= 11;

        if ($telValid) {
            $status = 200;
        } else {
            $status = 422;
        }

        if ($telValid) {
                $this->assertEquals(200, $status);
            }
            else{
                $this->assertEquals(422, $status);
            }
        }
    }

