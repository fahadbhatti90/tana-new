<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class biddingRuleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $rule_info;
    public $look_back_range;
    public $fileName;
    public $profileInfo;
    public $content;
    public $triggeredData;

    /**
     * Create a new message instance.
     *
     * @param $date
     * @param $rule_info
     * @param $fileName
     * @param $profileInfo
     * @param $triggeredData
     */
    public function __construct($date, $rule_info, $look_back_range, $fileName, $profileInfo, $triggeredData)
    {
        $this->date = $date;
        $this->rule_info = $rule_info;
        $this->fileName = $fileName;
        $this->profileInfo = $profileInfo;
        $this->triggeredData = $triggeredData;
        $this->look_back_range = $look_back_range;

        if ($this->profileInfo->is_active == 0) {
            $this->content = "'" . $this->rule_info->rule_name . "' Profile is now inactive and rule execution automatically stopped at " . $date . ".";
        } else {
            $this->content = $this->rule_info->rule_name . " has been executed at " . $date . ".";
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->fileName == "") {
            return $this->subject('Bidding Rule Execution - ' . $this->rule_info->rule_name)
                ->view('auth.emails.bidding');
        } else {
            return $this->subject('Bidding Rule Execution - ' . $this->rule_info->rule_name)
                ->attach($this->fileName)
                ->view('auth.emails.bidding');
        } // end else
    }
}
