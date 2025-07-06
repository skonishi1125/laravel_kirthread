<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\Library;
use Illuminate\Database\Seeder;

class LibraryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Library::truncate();

        $seeds = [
            [
                'id' => 1,
                'name' => '戦術論I: 治療師の観点から捉える補助職業の優位性',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>pタグで囲まれたコンテンツ。</p><br><p>改行後のコンテンツ。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 2,
                'name' => '魔導工学I: 魔導師使用学術の推論',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>ああああああああpタグで囲まれたコンテンツ。</p><br><p>改行後のコンテンツ。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 3,
                'name' => '0からわかる！戦闘のススメ',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>あ。</p><br><p>改行後のコンテンツ。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 4,
                'name' => '魔導工学II: 魔導師の観点から考える治癒魔法',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>あaaaaa。</p><br><p>改行後のコンテンツ。</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 5,
                'name' => "Ranger's skill: Learn By Reading",
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>sample。</p><br><p>改行後のコンテンツ。</p>',
                'required_clears' => 2,
            ],
            [
                'id' => 101,
                'name' => 'モンスター図鑑:草原',
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => '<p>草原はこの街周辺に広がる平原そのものを指す。この地域は魔物の巣窟となっている城から最も外れた地域のため、点在するモンスターも強力な個体は存在しないが、それでも冒険初心者には難敵となる存在もいくつかいるため注意が必要。</p><br><p>スララ いうまでもなく、弱い。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 201,
                'name' => '民話1: 伝説の英雄たち',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>昔、この国はモンスターが蔓延っていた。そこに3人の冒険者が現れ、人々のためにモンスターを討伐し続けたのだ。</p><br><p>彼らはのちに伝説と呼ばれる存在となった。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 202,
                'name' => '民話2: 英雄たちのその後',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>やがてこの国は人々で集まり、その人混みに紛れて英雄たちもいつの間にか姿を消した。</p>',
                'required_clears' => 0,
            ],
        ];

        foreach ($seeds as $seed) {
            Library::create($seed);
        }

    }
}
