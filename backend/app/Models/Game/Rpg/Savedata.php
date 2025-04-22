<?php

namespace App\Models\Game\Rpg;

use App\User;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Savedata extends Model
{
    use HasFactory;

    // データ作成時のデフォルトの所持金 どうせ貯まるので、多めに持たせてやろう
    public const DEFAULT_MONEY = 400;

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
            // 上記でまとめて消すことができるが、Partyモデル側のdeletingイベントが発火しないので個別に消していく。
            foreach ($savedata->parties as $party) {
                $party->delete();
            }
            $savedata->battle_state()->delete();
            $savedata->savedata_has_item()->delete();
            $savedata->savedata_cleared_Fields()->delete();
        });
    }

    /**
     * リレーションメモ
     * ->rpg_savedata 等
     *   こちらで受け取ると、そのDBのレコードをcollectionで受け取れる
     * ->rpg_savedata() 等
     *   ()を付与した形で受け取ると、クエリビルダとして取得する
     * createなど、そういったアクションを使う場合は()を付与して使うと良い。
     *
     * (例)
     *   // クリアしたフィールドの一覧をCollectionで取得
     *   $savedata->savedata_cleared_fields;
     *
     *   // savedata_cleared_fieldsに新しいレコードを追加する
     *   $savedata->savedata_cleared_fields()->create([
     *     'field_id' => 1
     *   ]);
     */

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

    public function savedata_cleared_fields(): HasMany
    {
        /**
         * tinkerでテストする場合の例
         *   $s = Savedata::first();
         *   $s->savedata_cleared_fields;
         *   $s->savedata_cleared_fields->first()->field;
         */
        return $this->hasMany(SavedataClearedField::class, 'savedata_id');
    }

    // savedataの持つアイテムの所持数を確認したいとき、$s->items[0]->pivot->possession_number で実現ができる
    public function items()
    {
        return $this
            ->belongsToMany(Item::class, 'rpg_savedata_has_items', 'savedata_id', 'item_id')
            ->withPivot('possession_number');
    }

    public function battle_state()
    {
        return $this->hasOne(BattleState::class, 'savedata_id');
    }

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
