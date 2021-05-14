<?php

namespace App\Domain\Entities;

use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Permission extends Eloquent
{
    use HybridRelations;
    protected $connection = 'mongodb';
    protected $collection = 'permissions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'alias', 'module',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class);
    }
}
