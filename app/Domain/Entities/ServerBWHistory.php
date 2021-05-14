<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ServerBWHistory extends Eloquent {

    use SoftDeletes,
        HybridRelations;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $connection = 'mongodb';
    protected $collection = 'server_bw_history';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'serverID',
        'upload',
        'download',
        'all',
        'createdTime',
    ];

}
