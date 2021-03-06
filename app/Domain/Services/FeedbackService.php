<?php


namespace App\Domain\Services;


use App\Domain\Entities\Feedback;
use App\Domain\Repositories\FeedbackRepository;

class FeedbackService
{

    private $feedbackRepository;

    /**
     * FeedbackService constructor.
     * @param $feedbackRepository
     */
    public function __construct(FeedbackRepository $feedbackRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
    }


    public function getAllFeedback($param)
    {
        return $this->feedbackRepository->getAllFeedback($param['pageSize']);
    }

    public function addFeedback($param)
    {
        $feedback = new Feedback();
        $feedback->rate_service = $param['rateService'];
        $feedback->rate_dish = $param['rateDish'];
        $feedback->content = $param['content'];
        $feedback->ts = time();

        $this->feedbackRepository->insert($feedback);
    }

}
