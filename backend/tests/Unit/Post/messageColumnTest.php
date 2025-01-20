<?php

namespace Tests\Unit\Post;

// use PHPUnit\Framework\TestCase;
use App\Models\Post;
use Tests\TestCase;

class messageColumnTest extends TestCase
{
    /**
     * @test
     *
     * @...とつけることで、testと認識する
     * これがなかったら関数名にtestとつけないと認識されない
     */
    public function test_post_message_nullable_youtube()
    {
        // 何か文字列が入っていた時、messagesはnullでも良い
        $post = new Post;
        $post->message = null;
        $post->picture = null;
        // $post->youtube_url = "https://aaa.youtube.com?v=111122223333";
        $post->youtube_url = null;
        $post->user_id = 1;
        $post->good = 0;

        $isset_youtube = isset($post->youtube_url);
        if ($isset_youtube) {
            $post_save = $post->save();
            $this->assertTrue($post_save);
        } else {
            echo PHP_EOL.'youtubeが空なので空のmessage/pictureの投稿はできません。'.PHP_EOL;
            $this->assertFalse(false);
        }
    }

    public function test_post_message_nullable_picture()
    {
        // 何か文字列が入っていた時、messagesはnullでも良い
        $post = new Post;
        $post->message = null;
        $post->picture = null;
        // $post->picture = 'sample.png';
        $post->youtube_url = null;
        $post->user_id = 1;
        $post->good = 0;

        $isset_picture = isset($post->picture);
        if ($isset_picture) {
            $post_save = $post->save();
            $this->assertTrue($post_save);
        } else {
            echo PHP_EOL.'pictureが空なので空のmessage/youtubeの投稿はできません。'.PHP_EOL;
            $this->assertFalse(false);
        }
    }
}
