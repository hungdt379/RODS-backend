<?php

namespace App\Domain\Entities;

use App\Common\Utility;
use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Group extends Eloquent
{
    use SoftDeletes, HybridRelations;
    protected $dates = ['created_at', 'updated_at','deleted_at'];
    protected $connection = 'mongodb';
    protected $collection = 'groups';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name','description','status'
    ];

    public function servers() {
        return $this->belongsToMany(Server::class);
    }

    /**
     * Scope a query to only include filter by Status.
     *
     * @param Builder $query
     * @param $status
     * @return Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', '=', $status);
    }

    /**
     * Scope a query to only include filter by created date.
     *
     * @param Builder $query
     * @param $startDate
     * @param $endDate
     * @return Builder
     * @throws \Exception
     */
    public function scopeCreateDate($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [new \DateTime($startDate), new \DateTime($endDate)]);
    }

    /**
     * Scope a query to only include filter by created date.
     *
     * @param Builder $query
     * @param $direction
     * @return Builder
     */
    public function scopeSort($query, $direction)
    {
//        $condition = $direction === 'desc' ? -1 : 1;
        return $query->orderBy('name', $direction);
    }
}
