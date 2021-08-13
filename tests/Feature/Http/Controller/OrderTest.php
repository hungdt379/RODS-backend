<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use JWTAuth;

class OrderTest extends TestCase
{
    /** @test */
    public function viewDetailConfirmOrder_TestCase_01()
    {
        $credentials = [
            'username' => 'BB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => 'aaaaaaa'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('detail-confirm-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function viewDetailConfirmOrder_TestCase_02()
    {
        $credentials = [
            'username' => 'BB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => 'aaaaaaa123'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('detail-confirm-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function viewDetailConfirmOrder_TestCase_03()
    {
        $credentials = [
            'username' => 'BB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => '1234566778'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('detail-confirm-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function viewDetailConfirmOrder_TestCase_04()
    {
        $credentials = [
            'username' => 'BB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('detail-confirm-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function viewDetailConfirmOrder_TestCase_05()
    {
        $credentials = [
            'username' => 'BB',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => '60d60dab983dd412f1555313'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
            ->get(route('detail-confirm-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }
}
