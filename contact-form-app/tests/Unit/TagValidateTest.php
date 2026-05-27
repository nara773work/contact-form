<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TagValidateTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_tag_new_create(): void
    {
        $tags = collect([
            ['name' => 'a'],
            ['name' => 'b'],
            ['name' => 'c']
        ]);

        $this->assertNull($tags->get(3));

        $tags->push(['name' => 'd']);       
        $tags->push(['name' => 'toolong']);  
        $tags->push(['name' => '']);
        $tags->push(['name' => 'a']);

        //文字制限内で、かつNOTNULLで、かつ一意性のあるタグ名を含むか検証する
        $filterd_correct = $tags->unique('name')->filter(function($item){
            $correct = mb_strlen($item['name']??'');
            return 1 <= $correct && $correct < 2;
        });

        $this->assertContains($tags[3], $filterd_correct);

        //文字制限外のタグ名を含まないか検証する
        $filterd_long = $tags->unique('name')->filter(function($item){
            $long = mb_strlen($item['name']??'');
            return 2 > $long ;
        });

        $this->assertNotContains($tags[4], $filterd_long);

        //nullのタグ名を含まないか検証する
        $filterd_null = $tags->unique('name')->filter(function($item){
            $null = mb_strlen($item['name']??'');
            return  0 === $null;
        });
        
        $this->assertNotContains($tags[4], $filterd_null);
    }

    public function test_tag_update(): void{
        $tags = collect([
            ['name' => 'a'],
            ['name' => 'b'],
            ['name' => 'c']
        ]);

        $contacts = collect([
            ['user1' => $tags->get(0)],
            ['user2' => $tags->get(1)]
        ]);

        if (!$contacts->contains(['name'=>'c'])) {
            $tags->put(2,['name' => 'd']); 
        }   
        
        $this->assertEquals('d',$tags->get(2)['name']);

        if (!$tags->contains(['name' => 'a'])) {
            $tags->put(1,['name' => 'e']); 
        } 
        $this->assertNotEquals('e',$tags->get(2)['name']);
    }
}
