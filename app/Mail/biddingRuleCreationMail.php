<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class biddingRuleCreationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $rule_name;
    public $statement;
    public $look_back_period;
    public $frequency;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $biddingRuleData
     */
    public function __construct($user, $biddingRuleData)
    {
        $statement = $biddingRuleData->getStatment();

        $look_back_period = ucwords(str_replace("_", " ", $biddingRuleData->look_back_period));
        $frequency = ucwords(str_replace("_", " ", $biddingRuleData->frequency));

        $this->user = $user;
        $this->rule_name = $biddingRuleData->rule_name;
        $this->statement = $statement;
        $this->look_back_period = $look_back_period;
        $this->frequency = $frequency;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bidding Rule Creation - '.$this->rule_name)->view('auth.emails.biddingCreation');
    }
}
