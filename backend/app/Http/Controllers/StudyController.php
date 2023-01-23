<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Post;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Illuminate\Support\Facades\DB;

class StudyController extends Controller
{
    //
    private $private_var = 100;

    public function useMonolog() {
        // create a log channel
        $log_path = storage_path() . '/logs/monolog.log';
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler($log_path, Logger::WARNING));

        // add records to the log
        $log->warning('Foo');
        $log->error('Bar');
    }

    public function useTransaction() {

        // logファイルの定義
        $log_path = storage_path() . '/logs/transaction.log';
        $log = new Logger('Transaction');
        $log->pushHandler(new StreamHandler($log_path, Logger::WARNING));

        try {
            // post を挿入し、消す処理を実装してみる
            DB::transaction(function () {
                $created_post = Post::create([
                    'message'   =>  'トランザクションで作ってます',
                    'picture'   =>  'transaction.pic',
                    'youtube_url'   =>  null,
                    'good' =>  '0',
                    'user_id'   =>  116 // testアカウント
                ]);
                throw new Exception('トランザクション有り！');
            });
        } catch (Exception $e) {
            $log->error('正常に動作が終了しませんでした.');
            $log->error(sprintf('%s %s %s', $e->getMessage(), $e->getFile(), $e->getLine()));
        }

    }

    public function notUseTransaction() {

        // logファイルの定義
        $log_path = storage_path() . '/logs/transaction.log';
        $log = new Logger('Transaction');
        $log->pushHandler(new StreamHandler($log_path, Logger::WARNING));

        try {
            // トランザクションなしだと、例外が発生してもpostが作られっぱなしになる
            $created_post = Post::create([
                'message'   =>  'トランザクションなしです',
                'picture'   =>  null,
                'youtube_url'   =>  null,
                'good' =>  '0',
                'user_id'   =>  116 // testアカウント
            ]);
            throw new Exception('トランザクションなし。');
        } catch (Exception $e) {
            $log->error('正常に動作が終了しませんでした.');
            $log->error(sprintf('%s %s %s', $e->getMessage(), $e->getFile(), $e->getLine()));
        }

    }

}
