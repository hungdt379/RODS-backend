<?php

namespace Tests\Feature\Http\Controller;

use Tests\TestCase;
use JWTAuth;

class AuthTest extends TestCase
{

    // test truong hop sai
    /** @test */
    public function login_TestCase_01()
    {
        $data = [
            'username' => 'BB',
            'password' => '123'
        ];

        $response = $this->post(route('login'), $data);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => $response['data']['token'],
                    'token_type' => 'Bearer',
                    'user' => [
                        'user_id' => JWTAuth::user()->_id,
                        'user_name' => JWTAuth::user()->username,
                        'role' => JWTAuth::user()->role,
                        'number_of_customer' => JWTAuth::user()->number_of_customer
                    ]
                ],
                'ts' => time()
            ]);
    }

    /** @test */
    public function login_TestCase_02()
    {
        $data = [
            'username' => 'QLB',
            'password' => '123'
        ];

        $response = $this->post(route('login'), $data);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => $response['data']['token'],
                    'token_type' => 'Bearer',
                    'user' => [
                        'user_id' => JWTAuth::user()->_id,
                        'user_name' => JWTAuth::user()->username,
                        'role' => JWTAuth::user()->role,
                        'number_of_customer' => JWTAuth::user()->number_of_customer
                    ]
                ],
                'ts' => time()
            ]);
    }

    /** @test */
    public function login_TestCase_03()
    {
        $data = [
            'username' => 'TN',
            'password' => '123'
        ];

        $response = $this->post(route('login'), $data);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => $response['data']['token'],
                    'token_type' => 'Bearer',
                    'user' => [
                        'user_id' => JWTAuth::user()->_id,
                        'user_name' => JWTAuth::user()->username,
                        'role' => JWTAuth::user()->role,
                        'number_of_customer' => JWTAuth::user()->number_of_customer
                    ]
                ],
                'ts' => time()
            ]);
    }

    // test truong hop sai
    /** @test */
    public function login_TestCase_04()
    {
        $data = [
            'username' => 'TN1',
            'password' => '123'
        ];

        $response = $this->post(route('login'), $data);

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'status' => false,
                'message' => 'Invalid Email or Password',
                'data' => [],
                'ts' => time()
            ]);
    }

    // test truong hop sai
    /** @test */
    public function login_TestCase_05()
    {
        $data = [
            'username' => 'TN',
            'password' => '1234'
        ];

        $response = $this->post(route('login'), $data);

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'status' => false,
                'message' => 'Invalid Email or Password',
                'data' => [],
                'ts' => time()
            ]);
    }

    /** @test */
    public function login_TestCase_06()
    {
        $data = [
            'username' => null,
            'password' => '1234'
        ];

        $response = $this->post(route('login'), $data);

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'status' => false,
                'message' => 'Invalid Email or Password',
                'data' => [],
                'ts' => time()
            ]);
    }

    /** @test */
    public function login_TestCase_07()
    {
        $data = [
            'username' => null,
            'password' => null
        ];

        $response = $this->post(route('login'), $data);

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'status' => false,
                'message' => 'Invalid Email or Password',
                'data' => [],
                'ts' => time()
            ]);
    }

    /** @test */
    public function login_TestCase_08()
    {
        $data = [
            'username' => 'TN',
            'password' => null
        ];

        $response = $this->post(route('login'), $data);

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'status' => false,
                'message' => 'Invalid Email or Password',
                'data' => [],
                'ts' => time()
            ]);
    }
}
