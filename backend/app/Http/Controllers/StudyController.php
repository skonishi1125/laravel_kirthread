<?php

namespace App\Http\Controllers;

use App\Jobs\WriteLogFile;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class StudyController extends Controller
{
    //
    private $private_var = 100;

    public function useMonolog()
    {
        // create a log channel
        $log_path = storage_path().'/logs/monolog.log';
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler($log_path, Logger::WARNING));

        // add records to the log
        $log->warning('Foo');
        $log->error('Bar');
    }

    public function useTransaction()
    {

        // logファイルの定義
        $log_path = storage_path().'/logs/transaction.log';
        $log = new Logger('Transaction');
        $log->pushHandler(new StreamHandler($log_path, Logger::WARNING));

        try {
            // post を挿入し、消す処理を実装してみる
            DB::transaction(function () {
                $created_post = Post::create([
                    'message' => 'トランザクションで作ってます',
                    'picture' => 'transaction.pic',
                    'youtube_url' => null,
                    'good' => '0',
                    'user_id' => 116, // testアカウント
                ]);
                throw new Exception('トランザクション有り！');
            });
        } catch (Exception $e) {
            $log->error('正常に動作が終了しませんでした.');
            $log->error(sprintf('%s %s %s', $e->getMessage(), $e->getFile(), $e->getLine()));
        }

    }

    public function notUseTransaction()
    {

        // logファイルの定義
        $log_path = storage_path().'/logs/transaction.log';
        $log = new Logger('Transaction');
        $log->pushHandler(new StreamHandler($log_path, Logger::WARNING));

        try {
            // トランザクションなしだと、例外が発生してもpostが作られっぱなしになる
            $created_post = Post::create([
                'message' => 'トランザクションなしです',
                'picture' => null,
                'youtube_url' => null,
                'good' => '0',
                'user_id' => 116, // testアカウント
            ]);
            throw new Exception('トランザクションなし。');
        } catch (Exception $e) {
            $log->error('正常に動作が終了しませんでした.');
            $log->error(sprintf('%s %s %s', $e->getMessage(), $e->getFile(), $e->getLine()));
        }

    }

    public function downloadPostCsv($user_id)
    {
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
            '投稿日',
        ];

        $f = fopen(storage_path().'/csv/post.csv', 'w');
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
        header('Content-Type: application/octet-stream');
        header('Content-Length: '.filesize(storage_path().'/csv/post.csv'));
        header('Content-Disposition: attachment; filename=post.csv');
        readfile(storage_path().'/csv/post.csv');

        // return redirect()->route('/');

    }

    // サンプルcsv
    private function csvDownloadSample()
    {
        // ヘッダ行
        $head = ['id', '名前', '説明', '価格'];

        // データ行
        $data = [
            ['00001', 'りんご', '12個です', '1,200'],
            ['00002', 'ぶどう', 'ひとつぶです', '10,200'],
            ['00003', 'なし', '1個です', '120'],
        ];

        $date = date('Ymd');
        // header("Content-Type: application/octet-stream");
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=testdata.csv');

        // ヘッダ行の文字コード変換
        foreach ($head as $key => $value) {
            \Log::info('value, keys', [$value, $key]);
            $head[$key] = mb_convert_encoding($value, 'SJIS', 'UTF-8');
        }

        // データ行の文字コード変換・加工
        foreach ($data as $data_key => $line) {
            foreach ($line as $line_key => $value) {
                // 0をエクセルで表示させたい
                if ($line_key == 0) {
                    $value = '="'.$value.'"';
                }
                // , があったらダブルクォーテーションで囲む
                if (strpos($value, ',')) {
                    $data[$data_key][$line_key] = mb_convert_encoding('"'.$value.'"', 'SJIS', 'UTF-8');
                } else {
                    $data[$data_key][$line_key] = mb_convert_encoding($value, 'SJIS', 'UTF-8');
                }
            }
        }
        echo implode($head, ',')."\r\n";

        foreach ($data as $key => $line) {
            \Log::info('line: ', [$line]);
            echo implode($line, ',')."\r\n";
        }
        exit;
    }

    public function DispatchWriteLogJob()
    {
        $message = 'controller test';
        WriteLogFile::dispatch($message);
    }

    public function studyLocalScope()
    {
        $recently_post_count = Post::Recently()->count();

        return '直近の投稿件数は'.$recently_post_count.'件です。';
    }

    public function studyAccessor()
    {
        $recently_posts = Post::Recently()->get();
        $kaiddd_count = 0;
        foreach ($recently_posts as $p) {
            if ($p->is_set_kaiddd_reaction) {
                $kaiddd_count++;
            }
        }

        return '直近1ヶ月の投稿でかいデデデさんのリアクションがついた投稿の件数は'.$kaiddd_count.'件です。';
    }

    // ミューテタは他の部分の処理に影響が出るので、コメントアウトしておく。
    // public function studyMutator() {
    //     $post = Post::find(1572);
    //     $post->message = 'aaa kakikomi';
    //     $post->save();
    //     return redirect()->route('/');
    // }

    public function testIframely()
    {
        $twitter_url = 'https://twitter.com/skirplus';
        $twitter_iframely_data = self::convertUrlToIframelyData($twitter_url);
        // $pixiv_url = 'https://www.pixiv.net/users/18393576';
        // $pixiv_iframely_data = self::convertUrlToIframelyData($pixiv_url);

        return view('study/iframely')
            ->with('twitter_iframely_data', $twitter_iframely_data);
    }

    private function convertUrlToIframelyData($url)
    {
        $api_access_url = 'https://iframe.ly/api/iframely?api_key='.config('app.iframely_api_key');
        $json_raw_data = file_get_contents($api_access_url.'&url='.$url); // 生データ取得
        $json_convert_data = mb_convert_encoding($json_raw_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

        return json_decode($json_convert_data, false);
    }

    public function importPostByCsvIndex()
    {
        return view('study/import_csv/index');
    }

    public function importPostByCsvStore(Request $request)
    {
        // バリデーション定義
        $validatedData = $request->validate([
            'importCsvFile' => 'required|mimes:csv,txt|mimetypes:text/plain',
        ]);

        // dd(
        //     // input nameの値から、様々な操作ができる
        //     $request->hasFile('importCsvFile'),
        //     $request->importCsvFile,
        //     $request->importCsvFile->get()
        // );

        /*  データ保存
          * DB::transactionの中では保存は行えない。
          * ファイルの保存などは、データベースの操作とは関係がないから。
        */
        $request->importCsvFile->storeAs('public/', 'importCsvPosts.csv');

        try {
            DB::transaction(function ($request) {
                // 保存箇所からデータを取得する
                $saved_csv_data = \Storage::disk('local')->get('public/importCsvPosts.csv');

                // 改行コードを合わせてcsvデータのcollection化を行う
                $saved_csv_data = str_replace(["\r\n", "\r"], "\n", $saved_csv_data);
                $collection_csv_data = collect(explode("\n", $saved_csv_data));

                // ヘッダーと値を分ける
                $csv_header = $collection_csv_data->first();
                $collection_csv_data = $collection_csv_data->filter(function ($value, $key) {
                    return $key > 0;
                });

                foreach ($collection_csv_data as $c) {
                    $c = explode(',', $c);
                    // 連番$c[0]は不要なので無視。
                    $message = $c[1];
                    $user_id = (int) $c[2];
                    Post::create([
                        'message' => $message,
                        'user_id' => $user_id,
                        'good' => 0,
                    ]);
                }
            });
        } catch (Exception $e) {
            \Log::error('csvの取り込みに失敗しました。', [$e->getMessage(), $e->getLine()]);

            return redirect()->route('study_import_csv_index');
        }

        $request->session()->flash('status', 'Task was successful!');

        return redirect()->route('study_import_csv_index');

    }

    public function checkSession(Request $request)
    {
        $value = $request->session()->get('MKTgce9nB4asY3fiwOPqajwoDIiaWNDHoVrHplBl');
        $data = $request->session()->all();

        // 値の操作
        $request->session()->put('users', 'skonishi');
        $users = session('users');
        $request->session()->forget('carts');

        // 買い物カートに入れた情報を格納しておく例 URL?sessionで値を入れた場合だけ動作させる。
        if ($request['session'] == 1) {
            $array = ['りんご', 'みかん', 'ブドウ'];
            $request->session()->put('carts', $array);
        }

        return view('study/session/check')
            ->with('carts', session('carts') ?? []);
    }

    public function testVue()
    {
        return view('study/vue/test');
    }
}
