<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;


class CartItem extends Model
{

    protected $connection = 'mongodb';
    protected $collection = 'cart_item';
    protected $fillable = ['product_id', 'cart_key', 'quantity', 'note', 'dish_in_combo'];
    // protected $primaryKey = ['cart_id', 'product_id'];

    public $incrementing = false;

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->hasOne(Menu::class);
    }
}
