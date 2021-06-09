<?php

namespace App\Http\Controllers;

use App\Domain\Services\FeedbackService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    use ApiResponse;

    private $feedbackService;

    /**
     * FeedbackController constructor.
     * @param $feedbackService
     */
    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    public function addFeedback()
    {
        $param = request()->all();
        $this->feedbackService->addFeedback($param);

        return $this->successResponse('', 'Gui feedback thanh cong, Cam on quy khach');
    }

}
