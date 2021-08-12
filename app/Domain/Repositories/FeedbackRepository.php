<?php


namespace App\Domain\Repositories;


use App\Domain\Entities\Feedback;

class FeedbackRepository
{
    public function getAllFeedback($pageSize){
        return Feedback::orderBy('ts', 'DESC')
            ->paginate((int)$pageSize);
    }

    public function insert($feedback)
    {
        $feedback->save();
    }

    public function update($feedback)
    {
        $feedback->update();
    }

    public function delete($feedback)
    {
        $feedback->delete();
    }
}
