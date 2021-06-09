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
        var_dump($param);
        return $this->feedbackRepository->getAllFeedback($param['pageSize']);
    }

    public function addFeedback($param)
    {
        $feedback = new Feedback();
        var_dump($param);
        $feedback->rate_service = $param['rateService'];
        $feedback->rate_dish = $param['rateDish'];
        $feedback->content = $param['content'];

        $this->feedbackRepository->insert($feedback);
    }

}
