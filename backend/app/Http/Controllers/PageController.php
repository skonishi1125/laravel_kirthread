<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function about() {
        $twitter_url = 'https://twitter.com/skirplus';
        $twitter_iframely_data = self::convertUrlToIframelyData($twitter_url);
        return view('about/about')
            ->with('twitter_iframely_data', $twitter_iframely_data);
    }

    public function game() {
      return view('game/index');
    }

    public function panel() {
      return view('game/panel');
    }

    private function convertUrlToIframelyData($url) {
        $api_access_url = 'https://iframe.ly/api/iframely?api_key=' . env('IFRAMELY_API_KEY');
        $json_raw_data = file_get_contents($api_access_url . '&url=' . $url); // 生データ取得
        $json_convert_data = mb_convert_encoding($json_raw_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        return json_decode($json_convert_data, false);
    }

}
