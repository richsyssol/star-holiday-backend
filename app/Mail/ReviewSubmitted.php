<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $isVideoReview;
    public $subject;

    public function __construct(Review $review, $isVideoReview = false, $subject = null)
    {
        $this->review = $review;
        $this->isVideoReview = $isVideoReview;
        $this->subject = $subject ?: ($isVideoReview ? 'New Video Review Submitted' : 'New Review Submitted');
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.review-submitted')
                    ->with([
                        'review' => $this->review,
                        'isVideoReview' => $this->isVideoReview,
                    ]);
    }
}