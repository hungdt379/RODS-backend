<?php

namespace Tests\Feature\Http\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use JWTAuth;

class CartTest extends TestCase
{
    /** @test */
    public function deleteItemInCart_TestCase_01()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'item_id' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('delete-item-in-cart'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function deleteItemInCart_TestCase_02()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'item_id[0]' => '60ec23b569698f3742470deb'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('delete-item-in-cart'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function deleteItemInCart_TestCase_03()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'item_id[0]' => '60ec23b569698f3742470deb',
            'item_id[1]' => '60ec23b569698f3742470dee'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('delete-item-in-cart'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function deleteItemInCart_TestCase_04()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'item_id[0]' => '60ec23b569698f3742470deb',
            'item_id[1]' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('delete-item-in-cart'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function deleteItemInCart_TestCase_05()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'item_id[0]' => 'abc',
            'item_id[1]' => '1234'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('delete-item-in-cart'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function deleteItemInCart_TestCase_06()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'item_id[0]' => '60ec23b569698f3742470deb',
            'item_id[1]' => '1234'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('delete-item-in-cart'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }

    /** @test */
    public function deleteItemInCart_TestCase_07()
    {
        $credentials = [
            'username' => 'MB01',
            'password' => '123'
        ];

        $token = JWTAuth::attempt($credentials);

        $data = [
            'item_id[0]' => '123sdasd'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])
            ->post(route('delete-item-in-cart'), $data);

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'ts'
            ]);
    }
}
