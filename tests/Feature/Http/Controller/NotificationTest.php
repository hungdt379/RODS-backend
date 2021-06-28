<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use JWTAuth;

class NotificationTest extends TestCase
{
    // ______________call waiter_______________________
    /** @test */
    public function callWaiter_TestCase_01()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'content' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('callWaiter'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function callWaiter_TestCase_02()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'content' => 100
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('callWaiter'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    public function callWaiter_TestCase_03()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'content' => 'gọi nước lọc'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('callWaiter'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    public function callWaiter_TestCase_04()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'content' => '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('callWaiter'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function callWaiter_TestCase_05()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'content' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('callWaiter'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function callWaiter_TestCase_06()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'content' => 100
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('callWaiter'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    public function callWaiter_TestCase_07()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'content' => 'gọi nước lọc'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('callWaiter'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    public function callWaiter_TestCase_08()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'content' => '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('callWaiter'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

}
