<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $reportInfo Report email information
     */
    public $reportInfo;

    /**
     * Create a new message instance.
     *
     * @param $reportInfo ReportInfo
     * @return void
     */
    public function __construct($reportInfo)
    {
        $this->reportInfo = $reportInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->reportInfo->title;
        $attachFile = $this->reportInfo->attachFile;
        return $this->view($title)->from('qat@ericsson.com')
            ->attach($attachFile);
    }
}
