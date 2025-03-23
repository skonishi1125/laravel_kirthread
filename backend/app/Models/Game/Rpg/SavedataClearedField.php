<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedataClearedField extends Model
{
    use HasFactory;

    protected $table = 'rpg_savedata_cleared_fields';

    protected $guarded = [
        'id',
    ];

    /**
     * tinkerでリレーションテストをするとき、検証用コードの例
     *   $cleared = App\Models\Game\Rpg\SavedataClearedField::first();
     *   $cleared->savedata;
     *   $cleared->field;
     */

    /**
     * @return belongsTo<Field, $this>
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    /**
     * @return belongsTo<Savedata, $this>
     */
    public function savedata(): BelongsTo
    {
        return $this->belongsTo(Savedata::class, 'savedata_id');
    }
}
