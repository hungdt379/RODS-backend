<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class SearchCombo129K extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'search_combo_129';

    protected $fillable = [
        '_id', 'name', 'cost', 'description', 'image', 'hotpot', 'category_id'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
