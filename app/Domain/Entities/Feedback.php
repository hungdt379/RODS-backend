<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Feedback extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'feedback';

    protected $fillable = [
        '_id', 'rate_service', 'rate_dish', 'content', 'ts'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
