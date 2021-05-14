<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ClassInformation extends Eloquent {
    use HybridRelations;
    protected $dates = ['created_at', 'updated_at', 'create_date'];
    protected $connection = 'mongodb';
    protected $collection = 'class_information';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'meeting_id',
        'internal_meeting_id',
        'create_date',
        'create_time',
        'dial_number',
        'running',
        'has_user_joined',
        'recording',
        'start_time',
        'end_time',
        'participant_count',
        'listener_count',
        'max_users',
        'moderator_count',
        'crawl_turn',
    ];

    public function server() {
        return $this->belongsTo(Server::class);
    }
}
