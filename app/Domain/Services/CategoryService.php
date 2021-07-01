<?php


namespace App\Domain\Services;


use App\Domain\Repositories\CategoryRepository;

class CategoryService
{
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function geAllCategory(){
        return $this->categoryRepository->getAllCategory();
    }

    public function getComboCategory(){
        return $this->categoryRepository->getCombo();
    }
}
