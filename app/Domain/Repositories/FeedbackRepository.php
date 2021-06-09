<?php


namespace App\Domain\Repositories;


class FeedbackRepository
{
    public function insert($feedback)
    {
        $feedback->save();
    }
}
