<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Actions\Fortify\CreateNewUser;
use Tests\TestCase;
use App\Models\User;

class fortifyTest extends TestCase
{
    use RefreshDatabase;
    public function test_fortify_create_new_user(): void
{
    $action = new CreateNewUser();
    $user = $action->create([
            'name' => 'ABC DEF',
            'email' => 'abc@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

    $this->assertDatabaseHas('users', [
        'name' => 'ABC DEF',
        'email' => 'abc@example.com',
        
        ]);
}

}
