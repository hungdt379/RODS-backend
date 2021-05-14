<?php

namespace App\Domain\Entities;

use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ServerAccount extends Eloquent {

    use HybridRelations;

    protected $dates = ['created_at', 'updated_at'];
    protected $connection = 'mongodb';
    protected $collection = 'server_account';

    /**
     * The attributes that are mass assignable.
     * Role
     * @var array
     */
    protected $fillable = [
        'name',
        'secretToken',
        'secretKey',
        'description',
        'type',
    ];

}
