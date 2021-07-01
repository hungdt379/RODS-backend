<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Menu extends Model
{
    const COMBO_129 = 'Combo nướng 129k';
    const COMBO_169 = 'Combo nướng 169k';
    const COMBO_209 = 'Combo lẩu nướng 209k';

    protected $connection = 'mongodb';
    protected $collection = 'menu';

    protected $fillable = [
        '_id', 'name', 'cost', 'description', 'image', 'hotpot', 'category_id'
    ];
}
