<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Menu extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'menu';

    protected $fillable = [
        '_id', 'name', 'cost', 'description', 'image', 'hotpot', 'category_id'
    ];
}
