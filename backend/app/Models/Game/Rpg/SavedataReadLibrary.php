<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedataReadLibrary extends Model
{
    use HasFactory;

    protected $table = 'rpg_savedata_read_libraries';

    protected $guarded = [
        'id',
    ];

    /**
     * @return belongsTo<Library, $this>
     */
    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class, 'library_id');
    }

    /**
     * @return belongsTo<Savedata, $this>
     */
    public function savedata(): BelongsTo
    {
        return $this->belongsTo(Savedata::class, 'savedata_id');
    }
}
