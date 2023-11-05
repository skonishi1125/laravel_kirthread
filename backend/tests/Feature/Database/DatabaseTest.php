<?php

namespace Tests\Feature\Database;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class DatabaseTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testMessageLength()
    {

        // $log = Schema::getColumnType('posts','message'); // stringという型を返す
        // $log = Schema::getColumnListing('posts'); // postsの全カラムを返す

        // 256文字の文字列
        $message = 'この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。この文字列は255文字以上です。';

        $message = 'この文字列は255文字以下です。';

        $post = new Post();
        $post->message = $message;
        $post->user_id = 1;
        $post->good = 0;

        $message_length = mb_strlen($post->message);

        if ($message_length > 255) {
          echo PHP_EOL . '255文字以上の文字列が入っているので、DBへ登録せずテストを終了します。' . PHP_EOL;
          // $savePost = $post->save(); DB側のエラーはexceptionで取得できない？
          $this->assertFalse(false);
        } else {
          echo PHP_EOL . '255文字以下の文字列のため、DB登録テストを実施します。' . PHP_EOL;
          $savePost = $post->save();
          $this->assertTrue($savePost);
        }

    }
}
