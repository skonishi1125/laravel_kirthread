<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class StudyController extends Controller
{
    //
    public function useMonolog() {
        // create a log channel
        $log_path = storage_path() . '/logs/monolog.log';
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler($log_path, Logger::WARNING));

        // add records to the log
        $log->warning('Foo');
        $log->error('Bar');
    }
}
