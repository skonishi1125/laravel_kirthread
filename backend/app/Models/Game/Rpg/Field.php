<?php

namespace App\Models\Game\Rpg;

use App\Enums\Rpg\FieldData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property bool $is_cleared
 */
class Field extends Model
{
    use HasFactory;

    protected $table = 'rpg_fields';

    // 難易度の幅
    private const DIFFICULTY_RANGE = 6;

    public function savedata_cleared_fields()
    {
        /**
         * tinkerでテストする場合の例
         *   $s = Savedata::find(2);
         *   $f->savedata_cleared_field; // 2 をクリアしているセーブデータの一覧がCollectonで返る
         *   $f->savedata_cleared_field->first()->savedata
         */
        return $this->hasMany(SavedataClearedField::class, 'field_id');
    }

    /**
     * 難易度を★☆☆☆☆ の形で返す。
     *
     * 裏面のみ、例外的に処理する
     */
    public function convertDifficultyStars(): string
    {
        // 裏面
        if ($this->id === FieldData::VastExpanse->value) {
            return '？';
        }
        $filled_stars = str_repeat('★', $this->difficulty);
        $empty_stars = str_repeat('☆', self::DIFFICULTY_RANGE - $this->difficulty);

        $difficulty_stars = $filled_stars.$empty_stars;

        return $difficulty_stars;
    }

    /**
     * セーブデータに応じて、現在選択可能なフィールドの一覧を取得する
     *
     * @return Collection<int,\App\Models\Game\Rpg\Field>
     */
    public static function acquireCurrentSelectableFieldList(Savedata $savedata)
    {
        // クリア済みのフィールドの数
        $cleared_count = $savedata->savedata_cleared_fields()->count();
        $cleared_field_ids = $savedata->savedata_cleared_fields->pluck('field_id')->toArray();

        // 茫洋の地 フラグ
        $is_okay_to_go_vast_expanse = false;
        // 耕作地, 古城のクリア && 特定の書籍を読んでいること
        $required_field_ids = [
            FieldData::DecayedFarmland->value,
            FieldData::AncientCastle->value,
        ];
        $has_read_book = $savedata->savedata_read_libraries->contains('library_id', Library::VAST_EXPANSE_FLAG_BOOK_ID);
        // $cleared_field_ids と$required_field_idsを比較して、耕作地, 古城どちらもクリアしていたら空配列が返りtrueとなる
        $has_required_clears = empty(array_diff($required_field_ids, $cleared_field_ids));
        $is_okay_to_go_vast_expanse = $has_read_book && $has_required_clears;

        // dd($is_okay_to_go_vast_expanse, $required_field_ids, $has_read_book, $has_required_clears, $cleared_field_ids, array_diff($required_field_ids, $cleared_field_ids));

        $fields = self::where('required_clears', '<=', $cleared_count)
            ->when($is_okay_to_go_vast_expanse, function ($q) {
                $q->orWhere('id', FieldData::VastExpanse->value);
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($field) use ($cleared_field_ids) {
                $field->is_cleared = in_array($field->id, $cleared_field_ids, true);

                return $field;
            });

        return $fields;

    }
}
