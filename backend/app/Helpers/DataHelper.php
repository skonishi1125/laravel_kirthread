<?php

namespace App\Helpers;

use Barryvdh\Debugbar\Facades\Debugbar;

class DataHelper
{
    /**
     * 配列またはオブジェクトからキーを使って安全に値を取得する
     *
     * RPG バフの計算時などに使用する。例えば以下のケース。
     * ・1ターン目はデータがないので、データを作る(この時、buffsはarray)
     * ・戦闘実行後、jsonベースにしてDB格納
     * ・2ターン目はデータがあるので、DBからjsonデータを取得する
     * ・DBから取得したデータは、json_decodeでstdClassに変換されている
     * そのため1ターン目と2ターン目以降で、呼び出し方が変わる。
     * $buff[$buffed_status_name]の部分で Cannot use object of type stdClass as array とうのエラーに遭遇しないようにするヘルパ。
     */
    public static function getValueFlexible(array|object $data, string $key, mixed $default = null): mixed
    {
        if (is_array($data) && array_key_exists($key, $data)) {
            Debugbar::debug('DataHelper::getValueFlexible() 【array】 ');

            return $data[$key];
        }
        if (is_object($data) && isset($data->{$key})) {
            Debugbar::debug('DataHelper::getValueFlexible() 【object】 ');

            return $data->{$key};
        }

        return $default;
    }
}
