<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;


class CartItem extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cart_item';
    protected $fillable = ['item_id', 'table_id', 'quantity', 'note', 'dish_in_combo', 'total_cost'];
    protected $hidden = ['updated_at', 'created_at'];
}
