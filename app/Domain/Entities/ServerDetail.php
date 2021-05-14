<?php

namespace App\Domain\Entities;

use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ServerDetail extends Eloquent
{
    use HybridRelations;
    protected $dates = ['created_at', 'updated_at'];
    protected $connection = 'mongodb';
    protected $collection = 'server_detail';

    /**
     * The attributes that are mass assignable.
     *Role
     * @var array
     */
    protected $fillable = [
        'architecture',
        'key_name',
        'instance_id',
        'instance_type',
        'instance_state',
        'subnet_id',
        'memory_size',
        'disk_size',
        'disk_type',
        'monitoring_state',
        'launch_time',
        'memory_usage',
        'disk_usage',
        'cpu_usage'
    ];

    /**
     * Relationship with Server table
     *
     */
    public function server() {
        return $this->belongsTo(Server::class);
    }
}
