<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Author;

class UpdateAuthorBookCountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $authorId;

    /**
     * Create a new job instance.
     *
     * @param int $authorId
     * @return void
     */
    public function __construct($authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $author = Author::find($this->authorId);
        if ($author) {
            $count = $author->books()->count();
            $author->update(['books_count' => $count]);
        }
    }
}
