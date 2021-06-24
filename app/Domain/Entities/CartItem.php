<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;


class CartItem extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cart_item';
    protected $fillable = ['product_id', 'cart_key', 'quantity', 'note', 'dish_in_combo', 'total_cost'];
}
