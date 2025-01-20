<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class WriteLogFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $monolog;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;

        // ログ設定
        $log_path = storage_path().'/logs/write_log_file.log';
        $this->monolog = new Logger('write_log_file');
        $this->monolog->pushHandler(new StreamHandler($log_path, Logger::INFO));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->monolog->info('info_test', [$this->message]);

    }
}
