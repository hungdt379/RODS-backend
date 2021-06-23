<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Cart extends Model
{
    const CART_STATUS_SENT = 'sent';
    const CART_STATUS_UNSENT = 'unsent';

    protected $connection = 'mongodb';
    protected $collection = 'cart';

//    protected $fillable = [
//        '_id',
//        'number_of_customer', 'table_id',
//        'table_name', 'status',
//        'combo',
//        'hotpot',
//        'side_dish_drink',
//        'total_cost',
//        'ts'
//    ];

    protected $fillable = ['cart_key','table_id'];
    public $incrementing = false;

    public function items () {
        return $this->hasMany('App\CartItem', 'Cart_id');
    }
}
