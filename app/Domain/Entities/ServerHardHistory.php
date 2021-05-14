<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ServerHardHistory extends Eloquent {

    use SoftDeletes,
        HybridRelations;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $connection = 'mongodb';
    protected $collection = 'server_hard_history';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'serverID',
        'cpuCore',
        'cpuUsedPercent',
        'diskTotal',
        'diskUsedPercent',
        'memoryTotal',
        'memoryUsedPercent',
        'all',
        'createdTime',
    ];

}
