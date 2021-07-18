<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Cart extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cart';


    protected $fillable = ['table_id', 'total_cost'];

    protected $hidden = ['updated_at', 'created_at'];
}
