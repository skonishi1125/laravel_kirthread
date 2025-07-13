<?php

namespace App\Models\Game\Rpg;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'rpg_boards';

    protected $guarded = [
        'id',
    ];

    // 冒険者掲示板 投稿取得数
    public const BBS_POST_NUM = 10;

    /**
     * 「書き込み一覧」に表示するメッセージの取得
     *
     * BANされている投稿は取得の対象外とするが、投稿者自身が閲覧する際は表示するようにする
     */
    public static function fetchPostWithBanPolicy(Savedata $savedata, int $fetch_num)
    {

        $messages = self::where(function ($query) use ($savedata) {
            $query->where('is_banned', 0)
                ->orWhere('savedata_id', $savedata->id);
        })
            ->orderBy('id', 'desc')
            ->limit($fetch_num)
            ->get();

        return $messages;
    }

    /**
     * 冒険者掲示板 投稿制限チェック処理
     *
     * 投稿は1日1回しか書き込めないようにしているので、そのチェック。AM 7:00リセット。
     *
     * @return bool
     */
    public static function checkIsAlreadyWrittenDay(int $savedata_id)
    {
        // 現在時刻
        $today_7am = Carbon::today('Asia/Tokyo')->addHours(7);
        $now = Carbon::now('Asia/Tokyo');

        // もし今がAM0:00〜6:59なら、前日7:00以降を参照するようにする
        if ($now->lt($today_7am)) {
            $today_7am->subDay();  // 前日の7時に戻す
        }

        // この7:00以降の投稿が存在するか
        $exists = self::where('savedata_id', $savedata_id)
            ->where('created_at', '>=', $today_7am)
            ->exists();

        return $exists;

    }
}
