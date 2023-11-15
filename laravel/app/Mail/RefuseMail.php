<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefuseMail extends Mailable {

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
        $mail = $this->view('emails.RefuseMail')->subject('Rechazo de Factura Electronica de '.session('company')->name_company);
        if(file_exists($this->msg["xml"])){
        $mail->attach($this->msg["xml"]);
        }  if(file_exists($this->msg["xmlF"])){
        $mail->attach($this->msg["xmlF"]);
        }  
        return $mail;
    }

}
