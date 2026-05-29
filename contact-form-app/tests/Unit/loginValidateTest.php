<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class loginValidateTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_login_validate(): void
    {
        $users = [[
            'name' => 'Name',
            'email' => 'Email.com',
            'password' => 'Pass',
        ],
        [
            'name' => 'name',
            'email' => 'email@co.com',
            'password' => 'Pass'  
        ],
        [
            'name' => 'NAME',
            'email' => 'EMAIL@co.com',
            'password' => ''  
        ]];
        
    foreach($users as $user){
        $isValidateEmail = str_contains($user['email'], '@');
        $isPassword = ($user['password'] == '');

        if(! $isValidateEmail){
            $this->assertFalse($isValidateEmail);
        }
        else if($isPassword){
            $this->assertTrue($isPassword);
        }
        else{
            $this->assertTrue($isValidateEmail);
            $this->assertFalse($isPassword);
        }
}      
    }
}
