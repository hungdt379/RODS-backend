<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'category';

    protected $fillable = [
        '_id', 'name'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
