<?php


namespace App\Http\Controllers;

use App\Domain\Services\CategoryService;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;
    private $categoryService;

    /**
     * CategoryController constructor.
     * @param $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getAllCategory(){
        $data = $this->categoryService->geAllCategory();
        return $this->successResponse($data, 'Success');
    }

}
