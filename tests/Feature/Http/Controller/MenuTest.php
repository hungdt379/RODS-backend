<?php

namespace Tests\Feature\Http\Controller;

use Tests\TestCase;
use JWTAuth;

class MenuTest extends TestCase
{
    /** @test */
    public function getAllItem_TestCase_01()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 1000,
            'pageSize' => 1000,
            'q' => 'abc'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_02()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 1000,
            'pageSize' => 1000,
            'q' => 123456
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_03()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 1000,
            'pageSize' => 1000,
            'q' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_04()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 1000,
            'pageSize' => 1000,
            'q' => 'khoai'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_05()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 1,
            'pageSize' => 10,
            'q' => 'abc'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_06()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 1,
            'pageSize' => 10,
            'q' => 123456
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_07()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 1,
            'pageSize' => 10,
            'q' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_08()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 1,
            'pageSize' => 10,
            'q' => 'khoai'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_09()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 'abc',
            'pageSize' => 'abc',
            'q' => 'abc'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_10()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 'abc',
            'pageSize' => 'abc',
            'q' => 123456
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_11()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 'abc',
            'pageSize' => 'abc',
            'q' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_12()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => 'abc',
            'pageSize' => 'abc',
            'q' => 'khoai'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_13()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => null,
            'pageSize' => null,
            'q' => 'abc'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_14()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => null,
            'pageSize' => null,
            'q' => 123456
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_15()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => null,
            'pageSize' => null,
            'q' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function getAllItem_TestCase_16()
    {
        $credentials = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'page' => null,
            'pageSize' => null,
            'q' => 'khoai'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('all-item'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }
}
