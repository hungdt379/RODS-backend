<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Server extends Eloquent {

    use SoftDeletes,
        HybridRelations;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $connection = 'mongodb';
    protected $collection = 'servers';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'architecture', 'description', 'ip_address', 'dns_name', 'provider', 'is_active', 'group_ids',
        'bbbUrl',
        'bbbSecret',
        'maxUsers', // số người tối đa server chứa được
        'usedUsers', // số người đã dùng rồi
        'meetings', // các meeting dùng server này
        'account',
        'externalID',
        'platform',
        'lock',
        'hasGroup',
    ];

    /**
     * Relationship with Group table
     *
     */
    public function groups() {
        return $this->belongsToMany(Group::class, null, 'server_ids', 'group_ids');
    }

    /**
     * Relationship with Server Detail table
     *
     */
    public function details() {
        return $this->hasMany(ServerDetail::class);
    }

    /**
     * Relationship with Class Information table
     *
     */
    public function classInformation() {
        return $this->hasMany(ClassInformation::class);
    }

    /**
     * Scope a query to only include filter by Status.
     *
     * @param Builder $query
     * @param $status
     * @return Builder
     */
    public function scopeStatus($query, $status) {
        return $query->where('is_active', '=', $status);
    }

    /**
     * Scope a query to only include filter by Status.
     *
     * @param Builder $query
     * @param $provider
     * @return Builder
     */
    public function scopeProvider($query, $provider) {
        return $query->where('provider', '=', $provider);
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
    public function scopeCreateDate($query, $startDate, $endDate) {
        return $query->whereBetween('created_at', [new \DateTime($startDate), new \DateTime($endDate)]);
    }

    /**
     * Scope a query to only include filter by created date.
     *
     * @param Builder $query
     * @param $direction
     * @return Builder
     */
    public function scopeSort($query, $direction) {
        return $query->orderBy('dns_name', $direction);
    }

    public function totalClass() {
        $lastCrawlTurn = ClassInformation::latest('crawl_turn')->pluck('crawl_turn')->first();
        $data = $this->classInformation()->where('crawl_turn', '=', $lastCrawlTurn)->get();
        return $data->count();
    }

    public function activeClass() {
        $lastCrawlTurn = ClassInformation::latest('crawl_turn')->pluck('crawl_turn')->first();
        $data = $this->classInformation()->where([['crawl_turn', '=', $lastCrawlTurn], ['running', '=', true]])->get();
        return $data->count();
    }

    public function activeParticipant() {
        $lastCrawlTurn = ClassInformation::latest('crawl_turn')->pluck('crawl_turn')->first();
        return $this->classInformation()->where([['crawl_turn', '=', $lastCrawlTurn], ['running', '=', true]])->sum('participant_count');
    }

}
