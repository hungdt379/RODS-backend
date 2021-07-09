<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use JWTAuth;

class UserTest extends TestCase
{
    /** @test */
    public function generateNewQrCode_TestCase_01()
    {
        $credentials = [
            'username' => 'TN',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => 'abcdef'
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
    public function generateNewQrCode_TestCase_02()
    {
        $credentials = [
            'username' => 'TN',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => 123456
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
    public function generateNewQrCode_TestCase_03()
    {
        $credentials = [
            'username' => 'TN',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => null
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
    public function generateNewQrCode_TestCase_04()
    {
        $credentials = [
            'username' => 'TN',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => '60e5e78b8b70000090000773'
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
