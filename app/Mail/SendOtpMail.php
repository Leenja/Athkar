<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $otp, public string $purpose = 'register')
    {
        //
    }

    public function envelope(): Envelope
    {
        $subject = $this->purpose === 'reset'
            ? 'Reset Password OTP - Athkar App'
            : 'OTP - Athkar App';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'otp' => $this->otp,
                'purpose' => $this->purpose,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
