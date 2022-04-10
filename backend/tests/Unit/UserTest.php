<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\User;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = new User();
        $response = $user->find(12);
        echo PHP_EOL . 'サンプルテストです' . PHP_EOL;
        $this->assertSame('山吹', $response['name']);
    }
}
