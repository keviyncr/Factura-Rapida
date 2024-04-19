<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PayMail extends Mailable {

    use Queueable,
        SerializesModels;

    public $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg) {
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $mail = $this->view('emails.payMail')->subject('Codigo de verifiación');
        return $mail;
    }

}
