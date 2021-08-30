<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class SearchCombo169K extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'search_combo_169';

    protected $fillable = [
        '_id', 'name', 'cost', 'description', 'image', 'hotpot', 'category_id', 'is_sold_out', 'add_to_cart'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
