<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Cart extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cart';


    protected $fillable = ['cart_key','table_id', 'total_cost'];

}
