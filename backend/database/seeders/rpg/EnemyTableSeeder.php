<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\Enemy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EnemyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        Enemy::truncate();

        $enemies = [
            // field 1
            [
                'id' => '1',
                'name' => 'スララ',
                'appear_field_id' => 1,
                'value_hp' => 30,
                'value_ap' => 0,
                'value_str' => 20,
                'value_def' => 2,
                'value_int' => 0,
                'value_spd' => 10,
                'value_luc' => 0,
                'exp' => 10,
                'drop_money' => 10,
                'portrait_image_path' => 'srara.png',
                'description' => '冒険者の前に立ちはだかる最初の敵。油断禁物！',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '2',
                'name' => 'ガオー',
                'appear_field_id' => 1,
                'value_hp' => 20,
                'value_ap' => 0,
                'value_str' => 30,
                'value_def' => 0,
                'value_int' => 0,
                'value_spd' => 20,
                'value_luc' => 0,
                'exp' => 30,
                'drop_money' => 15,
                'portrait_image_path' => 'gao.png',
                'description' => '素早い動きが特徴。早めに倒そう。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '3',
                'name' => 'ノラワニ',
                'appear_field_id' => 1,
                'value_hp' => 100,
                'value_ap' => 0,
                'value_str' => 25,
                'value_def' => 8,
                'value_int' => 10,
                'value_spd' => 15,
                'value_luc' => 0,
                'exp' => 100,
                'drop_money' => 50,
                'portrait_image_path' => 'norawani.png',
                'description' => '草むらを彷徨いている野良のワニ。バランスの良いステータスが特徴。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '4',
                'name' => 'イッカク',
                'appear_field_id' => 1,
                'value_hp' => 150,
                'value_ap' => 20,
                'value_str' => 25,
                'value_def' => 10,
                'value_int' => 50,
                'value_spd' => 30,
                'value_luc' => 0,
                'exp' => 200,
                'drop_money' => 100,
                'portrait_image_path' => 'ikkaku.png',
                'description' => '立派なツノから放たれる電撃が強力。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '5',
                'name' => 'オヤダマワニ',
                'appear_field_id' => 1,
                'value_hp' => 250,
                'value_ap' => 30,
                'value_str' => 50,
                'value_def' => 50,
                'value_int' => 20,
                'value_spd' => 40,
                'value_luc' => 30,
                'exp' => 250,
                'drop_money' => 500,
                'portrait_image_path' => 'oyadamawani.png',
                'description' => 'ノラワニ達を仕切る強力なワニ。鋭い歯を持ち、噛まれたらひとたまりもない。',
                'is_boss' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // field 2
            [
                'id' => '6',
                'name' => 'ハイスララ',
                'appear_field_id' => 2,
                'value_hp' => 120,
                'value_ap' => 20,
                'value_str' => 40,
                'value_def' => 10,
                'value_int' => 30,
                'value_spd' => 60,
                'value_luc' => 0,
                'exp' => 200,
                'drop_money' => 900,
                'portrait_image_path' => 'highsrara.png',
                'description' => 'スララの突然変異体。バランスの良いパラメータを持つ。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // field 3
            [
                'id' => '11',
                'name' => 'フレアドラゴ',
                'appear_field_id' => 3,
                'value_hp' => 4000,
                'value_ap' => 100,
                'value_str' => 100,
                'value_def' => 80,
                'value_int' => 400,
                'value_spd' => 250,
                'value_luc' => 100,
                'exp' => 4500,
                'drop_money' => 2500,
                'portrait_image_path' => 'flaredrago.png',
                'description' => '非常に知能の高い龍で、狡猾な手口を使い冒険者を餌とする。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($enemies as $enemy) {
            Enemy::create($enemy);
        }

    }
}
