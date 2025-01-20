<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = new User;
        $response = $user->find(12);
        echo PHP_EOL.'サンプルテストです'.PHP_EOL;
        $this->assertSame('山吹', $response['name']);
    }
}
