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

    // 難易度の幅。最大5
    private const DIFFICULTY_RANGE = 5;

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
        $is_okay_to_go_vast_expanse = $savedata->savedata_read_libraries->contains('library_id', Library::VAST_EXPANSE_FLAG_BOOK_ID);

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
