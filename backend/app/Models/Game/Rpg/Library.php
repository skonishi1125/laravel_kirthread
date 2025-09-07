<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $table = 'rpg_libraries';

    public const CATEGORY_ADVENTURE = 0;

    public const CATEGORY_JOB = 1;

    public const CATEGORY_ENEMY = 2;

    public const CATEGORY_HISTORY = 3;

    /**
     * セーブデータ進行度に応じて、閲覧可能な書籍を取得
     *
     * 引数でどのカテゴリの書籍を取得するか指定できる。
     */
    public static function fetchReadableLibraryList(Savedata $savedata, int $book_category = 0)
    {

        $cleared_count = $savedata->savedata_cleared_fields()->count();
        $cleared_field_ids = $savedata->savedata_cleared_fields()->pluck('field_id');

        return self::where('book_category', $book_category)
            ->where(function ($q) use ($cleared_count, $cleared_field_ids) {
                $q->where('required_clears', '<=', $cleared_count)
                // クリアしたフィールドが存在するなら、そちらでも絞り込みを行う
                    ->when($cleared_field_ids->isNotEmpty(), function ($q) use ($cleared_field_ids) {
                        $q->orWhereIn('required_clear_field_id', $cleared_field_ids);
                    });
            })
            ->orderBy('id', 'asc')
            ->get();

    }
}
