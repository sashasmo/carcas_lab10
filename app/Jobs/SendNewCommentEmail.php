<?php

namespace App\Jobs;

use App\Mail\CommentAddedMail;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewCommentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
      
    /**
     * The email of the author
     *
     * @var string
     */
    public $email;
    
    /**
     * The name of the article
     *
     * @var string
     */
    public $article_name;
 
    /**
     * Create a new job instance.
     * 
     * @param  string  $email
     * @param  string  $article_name
     * @return void
     */ 
    public function __construct(string  $email, string  $article_name)
    { 
        $this->email = $email;
        $this->article_name = $article_name;
    }
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new CommentAddedMail($this->email, $this->article_name));
    }
}
