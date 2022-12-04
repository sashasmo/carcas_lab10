<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentAddedMail extends Mailable
{
    use Queueable, SerializesModels;    
    /**
     * The name of the article
     *
     * @var string
     */
    public $article_name;
    /**
     * The email of the author
     *
     * @var string
     */
    public $email;
    /**
     * Create a new message instance.
     * @param string $email
     * @param string $article_name
     * 
     * @return void
     */
    public function __construct(string $email, string $article_name)
    {
        $this->email = $email;
        $this->article_name = $article_name;
    }
    /*
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('maria.barnash@gmail.com', 'Laravel Blog')
            ->to($this->email, 'you')
            ->subject('Alert: ' . 'Laravel Blog')
            ->view('mail.comment', ['text' => 'A new comment was added to your article "'.$this->article_name.'".',]);
    }
}