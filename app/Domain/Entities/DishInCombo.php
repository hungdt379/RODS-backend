<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class DishInCombo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'dish_in_combo';

    protected $fillable = [
        '_id', 'pid', 'name'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
