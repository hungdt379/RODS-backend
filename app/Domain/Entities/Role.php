<?php

namespace App\Domain\Entities;

use App\User;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Role extends Eloquent
{
    use SoftDeletes, HybridRelations;
    protected $dates = ['created_at', 'updated_at','deleted_at'];
    protected $connection = 'mongodb';
    protected $collection = 'roles';

    /**
     * The attributes that are mass assignable.
     *Role
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class);
    }
}
