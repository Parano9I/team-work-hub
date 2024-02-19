<?php

namespace TeamWorkHub\Modules\Invitation\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use TeamWorkHub\Modules\Invitation\DataTransferObjects\MailInvitation;
use TeamWorkHub\Modules\Invitation\Models\Invitation;

class Invite extends Mailable
{
    use Queueable, SerializesModels;

    private readonly MailInvitation $invitation;

    /**
     * InvitationCreate a new message instance.
     */
    public function __construct(MailInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invite',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'invitation_module::mail.invitation',
            with: [
                'first_name' => $this->invitation->firstName,
                'last_name'  => $this->invitation->lastName,
                'link'       => $this->invitation->url
            ]
        );
    }
}
