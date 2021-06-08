<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Category;

class CategoryRepository
{
    public function getAllCategory(){
        return Category::all();
    }

}
