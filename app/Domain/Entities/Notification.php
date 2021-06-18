<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Notification extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notification';

    protected $fillable = [
        '_id', 'user_id','user_fullname', 'read', 'title', 'content', 'ts'
    ];
}
