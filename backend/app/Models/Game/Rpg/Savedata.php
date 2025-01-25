<?php

namespace App\Models\Game\Rpg;

use App\User;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaveData extends Model
{
    use HasFactory;

    protected $table = 'rpg_savedatas';

    protected $guarded = [
        'id',
    ];

    public static function boot()
    {
        parent::boot();

        // 削除した時、セーブデータに紐づく情報もすべて削除する
        static::deleting(function ($savedata) {
            // $savedata->parties()->delete();
            // でまとめて消すことができるが、Partyモデル側のイベントが発火しないので個別に消していく。
            foreach ($savedata->parties as $party) {
                $party->delete(); // Party側のdeletingイベントを発火させる
            }
            $savedata->battle_state()->delete();
            $savedata->savedata_has_item()->delete();
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parties()
    {
        return $this->hasMany(Party::class, 'savedata_id');
    }

    public function savedata_has_item()
    {
        return $this->hasOne(SavedataHasItem::class, 'savedata_id');
    }

    // savedataの持つアイテムの所持数を確認したいとき、$s->items[0]->pivot->possesion_number で実現ができる
    public function items()
    {
        return $this
            ->belongsToMany(Item::class, 'rpg_savedata_has_items', 'savedata_id', 'item_id')
            ->withPivot('possesion_number');
    }

    public function battle_state()
    {
        return $this->hasOne(BattleState::class, 'savedata_id');
    }

    // リレーションメモ
    // 1:1でリレーションしているものは rpg_savedata で受け取る(単体取得になる)
    // 1:nでリレーションしているものは rpg_savedata()->get() みたいな形で受け取る(カッコつける)
    // ->追記: 違うかも rpg_savedataだとcollectionとして取得するが、rpg_savedata(だとクエリビルダとして取得できる
    public static function getLoginUserCurrentSavedata()
    {
        if (Auth::check() == false) {
            return null;
        } else {
            /** @var \App\User $user */
            $user = Auth::user();

            return $user->rpg_savedata;
        }
    }

    public static function checkSavedataHasParties()
    {
        $is_exist_parties_data = null;
        $savedata = self::getLoginUserCurrentSavedata();
        if ($savedata == null) {
            return false;
        }
        $parties = $savedata->parties;
        // 登録されているケースは3人である前提だが、バグとかで一人しか登録されてなくても通っちゃう
        // 大丈夫かな？
        $parties->isEmpty() ? $is_exist_parties_data = false : $is_exist_parties_data = true;

        return $is_exist_parties_data;
    }
}
