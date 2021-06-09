<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Category;

class CategoryRepository
{
    public function getAllCategory(){
        return Category::all();
    }

    public function getCombo()
    {
        return Category::where('name', 'combo')->first();
    }

    public function getDink()
    {
        return Category::where('name', 'drink')->first();
    }

    public function getFast()
    {
        return Category::where('name', 'fast')->first();
    }

}
