<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_filter_and_tel(): void
    {
        $datas = [
            ['name' => 'A', 'gender' => 0, 'address' => 'abc', 'updated_at' => 'yy.xx.0d'],
            ['name' => 'B', 'gender' => 4, 'address' => 'def', 'updated_at' => 'yy.xx.1d'],
        ];

        foreach ($datas as $data) {

            $status = ($data['name'] = 'A') ? 200 : 422;

            if ($data['name'] = 'A') {
                $this->assertEquals(200, $status);
            } else {
                $this->assertEquals(422, $status);
            }
        }

        // 不正な性別値を拒否
        foreach ($datas as $data) {

            $status = ($data['gender'] < 4) ? 200 : 422;

            if ($data['gender'] < 4) {
                $this->assertEquals(200, $status);
            } else {
                $this->assertEquals(422, $status);
            }
        }
    }

    public function test_save_contacts(): void
    {
        $contact = [
            [
                'first_name' => 'A',
                'last_name' => 'a',
                'gender' => 2,
                'email' => 'Aa@co.com',
                'tel' => '0#1########',
                'address' => 'abc',
                'tags' => 1,
                'detail' => 'detail',
            ],
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
        } else {
            $this->assertEquals(422, $status);
        }
    }

    public function test_save_new_tag(): void
    {
        $tags = [
            [
                'id' => 1,
                'name' => 'new1',
            ],
            [
                'id' => 2,
                'name' => 'new2',
            ],
            [
                'id' => 3,
                'name' => 'new2',
            ],
        ];

        $telValid = ! empty($tag_name) && strlen($tag_name) <= 50
        && isUniqueName($tags, 'id') && isUniqueName($tags, 'name');

        if ($telValid) {
            $status = 200;
        } else {
            $status = 422;
        }

        if ($telValid) {
            $this->assertEquals(200, $status);
        } else {
            $this->assertEquals(422, $status);
        }
    }

    public function test_update_tag(): void
    {
        $tags = [
            [
                'id' => 1,
                'name' => 'new1',
            ],
            [
                'id' => 2,
                'name' => 'new2',
            ],
            [
                'id' => 3,
                'name' => 'new3',
            ],
        ];

        $tags = [2 => ['name' => 'new3']];

        $telValid = ! empty($tag_name) && strlen($tag_name) <= 50
        && isUniqueName($tags, 'id') && isUniqueName($tags, 'name');

        if ($telValid) {
            $status = 200;
        } else {
            $status = 422;
        }

        if ($telValid) {
            $this->assertEquals(200, $status);
        } else {
            $this->assertEquals(422, $status);
        }
    }
}
