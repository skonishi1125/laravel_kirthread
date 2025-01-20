<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class createPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirthread:create_post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sample command. create post';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        \Log::info('createPostコマンドを実行します。');

        $post = Post::create([
            'message' => 'artisanコマンドで作成。',
            'user_id' => 1,
            'good' => 0,
        ]);

    }
}
