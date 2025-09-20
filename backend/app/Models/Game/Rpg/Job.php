<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'rpg_jobs';

    protected $guarded = [
        'id',
    ];

    public const GRADE_BEGINNER = 0;

    public const GRADE_GENERAL = 1;

    public const GRADE_EXPERT = 2;

    public const GRADE_PROFESSIONAL = 3;

    public const GRADE_HERO = 4;

    public const GRADE_LABELS = [
        self::GRADE_BEGINNER => '見習い労働者',
        self::GRADE_GENERAL => '初級労働者',
        self::GRADE_EXPERT => '労働者リーダー',
        self::GRADE_PROFESSIONAL => 'マスター労働者',
        self::GRADE_HERO => '伝説の日雇い労働者',
    ];

    // グレード毎のレート
    public const PAYMENT_RATES = [
        self::GRADE_BEGINNER => 0.1,
        self::GRADE_GENERAL => 0.2,
        self::GRADE_EXPERT => 0.5,
        self::GRADE_PROFESSIONAL => 1.0,
        self::GRADE_HERO => 2.0,
    ];

    // グレード達成のクリック基準値
    public const GRADE_COUNT_REFERENCE_VALUE = [
        self::GRADE_BEGINNER => 0,
        self::GRADE_GENERAL => 100,
        self::GRADE_EXPERT => 300,
        self::GRADE_PROFESSIONAL => 800,
        self::GRADE_HERO => 1500,
    ];

    public function savedata()
    {
        return $this->belongsTo(Savedata::class);
    }

    public static function calculateGradeByCount(int $total_count): int
    {
        // GRADE_COUNT_REFERENCE_VALUE を降順にソート
        $grade_list = collect(self::GRADE_COUNT_REFERENCE_VALUE)->sortDesc();

        foreach ($grade_list as $grade => $required_count) {
            if ($total_count >= $required_count) {
                return $grade;
            }
        }

        return self::GRADE_BEGINNER; // 念の為、BEGINNERを返す
    }
}
