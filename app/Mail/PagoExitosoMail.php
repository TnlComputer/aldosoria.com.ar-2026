<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoExitosoMail extends Mailable
{
  use Queueable, SerializesModels;

  public $datos;

  /**
   * Create a new message instance.
   */
  public function __construct($datos)
  {
    $this->datos = $datos;
  }

  /**
   * Build the message.
   */
  public function build()
  {
    return $this->subject('Confirmación de pago exitoso')
      ->view('emails.pago_exitoso');
  }
}