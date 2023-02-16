<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Post;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Illuminate\Support\Facades\DB;

use App\Jobs\WriteLogFile;

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

    public function downloadPostCsv($user_id) {
        // self::csvDownloadSample();

        $posts = Post::select('id', 'message', 'picture', 'youtube_url', 'user_id', 'created_at')
            ->where('user_id', $user_id)
            ->orderByDesc('id')
            ->get()
            ->toArray();

        $head = [
            '投稿ID',
            'メッセージ',
            '画像名',
            'YouTube URL',
            ' ユーザーID',
            '投稿日'
        ];

        $f = fopen(storage_path() . '/csv/post.csv', 'w');
        if ($f) {
            // カラム書き込み
            mb_convert_variables('SJIS', 'UTF-8', $head);
            fputcsv($f, $head);

            // データ書き込み
            foreach ($posts as $post) {
                mb_convert_variables('SJIS', 'UTF-8', $post);
                fputcsv($f, $post);
            }
        }
        fclose($f);

        // HTTPヘッダ
        header("Content-Type: application/octet-stream");
        header('Content-Length: '.filesize(storage_path() . '/csv/post.csv'));
        header('Content-Disposition: attachment; filename=post.csv');
        readfile(storage_path() . '/csv/post.csv');

        // return redirect()->route('/');

    }

    // サンプルcsv
    private function csvDownloadSample() {
        // ヘッダ行
        $head = ['id', '名前', '説明', '価格'];

        // データ行
        $data = [
            ["00001", 'りんご', '12個です', '1,200'],
            ["00002", 'ぶどう', 'ひとつぶです', '10,200'],
            ["00003", 'なし', '1個です', '120']
        ];

        $date = date("Ymd");
        // header("Content-Type: application/octet-stream");
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=testdata.csv");
        
        // ヘッダ行の文字コード変換
        foreach ($head as $key => $value) {
            \Log::info('value, keys', [$value, $key]);
            $head[$key] = mb_convert_encoding($value, "SJIS", "UTF-8");
        }
        
        // データ行の文字コード変換・加工
        foreach ($data as $data_key => $line) {
            foreach ($line as $line_key => $value) {
                // 0をエクセルで表示させたい
                if ($line_key == 0) {
                    $value = '="' . $value . '"';
                }
                // , があったらダブルクォーテーションで囲む
                if (strpos($value, ',')) {
                    $data[$data_key][$line_key] = mb_convert_encoding('"' . $value . '"', "SJIS", "UTF-8");
                } else {
                    $data[$data_key][$line_key] = mb_convert_encoding($value, "SJIS", "UTF-8");
                }
            }
        }
        echo implode($head, ",") . "\r\n";
        
        foreach ($data as $key => $line) {
            \Log::info('line: ', [$line]);
            echo implode($line, ",") . "\r\n";
        }
        exit;
    }

    public function DispatchWriteLogJob() {
        $message = 'controller test';
        WriteLogFile::dispatch($message);
    }

}
