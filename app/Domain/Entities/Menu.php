<?php


namespace App\Domain\Entities;


use Jenssegers\Mongodb\Eloquent\Model;

class Menu extends Model
{
    const COMBO_129 = 'Combo nướng 139k';
    const COMBO_169 = 'Combo nướng 169k';
    const COMBO_209 = 'Combo lẩu nướng 249k';

    protected $connection = 'mongodb';
    protected $collection = 'menu';

    protected $fillable = [
        '_id', 'name', 'cost', 'image', 'hotpot', 'category_id'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
