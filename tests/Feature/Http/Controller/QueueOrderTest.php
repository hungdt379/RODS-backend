<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QueueOrderTest extends TestCase
{
    /** @test */
    public function confirmQueueOrder_TestCase_01()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('confirm-queue-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function confirmQueueOrder_TestCase_02()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => '123123123'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('confirm-queue-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function confirmQueueOrder_TestCase_03()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => 'asdfghjkl'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('confirm-queue-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function confirmQueueOrder_TestCase_04()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'table_id' => '60d60dab983dd412f1555313'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('confirm-queue-order'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }
}
