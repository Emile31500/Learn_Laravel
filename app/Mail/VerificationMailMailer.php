<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationMailMailer extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @param App\Models\User $user
     * 
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from('emile00013@gmail.com', '')
                     ->with(['email' => $this->user->email])
                     ->view('verification');


        // $this->withSwiftMessage(function ($message) {
        //     $message->getHeaders()->addTextHeader(
        //         'Custom-Header', 'Verification de l\'adresse email'
        //     );
        // });
        // var_dump($this);
        // die;
        
        return $this;
    }
}
