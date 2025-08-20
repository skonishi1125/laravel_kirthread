<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\EnemyData;
use App\Enums\Rpg\FieldData;
use App\Models\Game\Rpg\Enemy;
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

        // 魔物図譜 草原
        $grassland_preface = '<p>ここで述べる草原とは、この街周辺に広がる地帯一帯を指す。<br>魔物の巣窟となっている古城より最も離れに位置する地域のため、点在するモンスターも強力な個体は存在しない。ただしそれでも、初心者の冒険者にとっては難敵と言える個体も存在するため充分な警戒は必要。</p>';
        $grassland_content = $this->buildEnemyHTMLElement(
            EnemyData::grasslandAppearingEnemies(),
            $grassland_preface
        );

        // 砂漠
        $desert_preface = '<p>砂漠。</p>';
        $desert_content = $this->buildEnemyHTMLElement(
            EnemyData::desertAppearingEnemies(),
            $desert_preface
        );

        // 火山
        $volcano_preface = '<p>あつい。</p>';
        $volcano_content = $this->buildEnemyHTMLElement(
            EnemyData::volcanoAppearingEnemies(),
            $volcano_preface
        );

        // 海岸
        $coast_preface = '<p>知能が高く魔法を使う魔物が多い。</p>';
        $coast_content = $this->buildEnemyHTMLElement(
            EnemyData::coastAppearingEnemies(),
            $coast_preface
        );

        $seeds = [
            [
                'id' => 1,
                'name' => '冒険の前に「スキル」と「アイテム」！',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>見習い冒険者の皆さん、こんにちは！<br>スララ、みんな簡単に倒しているけどなかなか手強い...<br>そんなことを思っているアナタ。いきなり冒険に出ていませんか？</p><p>準備もなしに冒険に出ること...正直無謀です！<br>冒険には準備がつきもの！<br>特に最序盤の準備の中でも大事なのは、とにかく「スキル」と「アイテム」です！<br></p><p>見習いとはいえ冒険者なら、何かしらの職業に就いているはずですよね。<br>今すぐこの本を読み終えて、ステータス画面を開いてスキルを覚えましょう！<br>次にショップに行って、アイテムを買っておくこともお忘れなく。</p><p>とにかく最初、冒険に出るときは「スキル」と「アイテム」！</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 2,
                'name' => '優しく解説！戦闘術〜プチ回復編〜',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>初学者の方に向けて、戦闘の技術について優しく解説します。<br>ずっと使える知識なので、覚えておくとためになりますよ。</p><p>街からフィールドに出ると、モンスターとの戦いが連続して発生する形となります。<br>モンスターを全員倒すと経験値が貰えますが、同時にHPとAPも少量回復します。<br>また、レベルが上がるとHPとAPが全回復します！</p><p>つまり戦闘では毎回APを使ってしまっても問題は無いですし、<br>レベルアップ手前のメンバーがいた場合は、戦闘でガンガンAPを使ってしまって良いということです！<br>次のレベルアップまでのEXPはステータス画面から確認ができますよ。</p><p>次回は逃亡時、全滅時について解説します。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 3,
                'name' => '優しく解説！戦闘術〜逃亡・全滅編〜',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>初学者の方に向けて、戦闘の技術について優しく解説します。<br>今回は逃走時、全滅時の違いについてです。<br>あまり考えたいことではありませんが、もしもの時に役立ちますよ。</p><p></p><p>逃走した時は、「使ったアイテム」、「今まで獲得した経験値やレベル」が現時点の状態で街に戻ることになります。探索の中断と同じですね。<br>一方ですが全滅してしまった時は、「使ったアイテム」、「今まで獲得した経験値やレベル」が冒険前の状態に巻き戻った状態で街に戻ることになります。<br></p><p>全滅は大変なことのように見えますが、アイテムをたくさん使って結果的にフィールドの探索ができなかった時でも無駄にアイテムを消費してしまうことを防げますよ。</p><p>反省して、もう一度冒険にトライしてみましょう！</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 4,
                'name' => '格闘家の在り方I【格闘家】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>格闘家は高い物理火力が売りの職業だ！<br>味方の構成は気にせず、とにかく必要だと思うスキルを会得していけば良いだろう！</p><p>火力と同じほど自信があるのが素早さだ！<br>敵味方の中で先手を取ることが殆どだろう！アイテムを持っているなら速さを活かして味方を支援するのも素晴らしい！</p><p>体力も高めではあるが、軽装しか基本的に纏えないため耐久力はさほどない！<br>また魔法への耐性も皆無と言って良いだろう！自身の打たれ強さを過信しないことだ！<br>いわゆるAPも充分に持ち合わせていない！<br>長時間の戦闘が不安ならばポイントを割り振り補強するのも悪くないだろう！</p><p>悲観的なことも書いてしまったが、高速火力というだけでお釣りが来るほど役割を果たすことができる！格闘家という職業に誇りを持って戦えることを願う！</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 5,
                'name' => '癒し手としてI【治療師】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>治療師は聖なる力で味方を助ける、支援型の職業です。</p><p>まずは回復系のスキルを会得するよう励みましょう。<br>基礎とも言える単体回復スキルは他の職業も会得できますが、経験を積めば豊富な回復手段を覚えることができます。</p><p>魔法攻撃スキルは、パーティ全体のバランスを見て要否を判断すると良いでしょう。<br>治療師の中には、聖なる力を攻撃に変えられるよう鍛錬する方もいらっしゃるようですが・・・。</p><p>物理攻撃は基本的に不向きです。<br>耐久力は無いことはないのですが、良いわけでもありませんので過信は禁物です。</p><p>レベルが上がることで得られるステータスポイントについては、強みである知力や、状況に応じて素早さに振り分けていくのも良いですね。</p><p>治療師として大事なことは、仲間を戦闘不能にさせないように立ち回ることです。<br>この本を読み終えたあなたならきっと意識できますよ。</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 7,
                'name' => '騎士の担う役割I【重騎士】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>重騎士は秀でた耐久力で味方の壁となる、守りの職業である。</p><p>重騎士の基礎であり、象徴とも言える「ワイドガード」は唯一無二の性能を誇る。<br>打たれ弱い味方が居る場合は重宝すること間違い無いだろう。<br>ただし自分の経験が少ないままスキルレベルを上げ過ぎてしまうと、<br>AP切れが発生しやすくなり、大事な場面で使えなくなるケースには気をつけることだ。</p><p>「ワイドガード」「ガードアップ」の効果は自身の防御力に依存して上昇する。<br>優秀な防御力をさらにポイントを割り振り高めていっても構わない。</p><p>物理火力に自信のないパーティ構成なら、アタッカーとして立ち回っても良いだろう。<br>行動速度は遅いが、安定した火力を出せるようになるはずだ。</p><p>パーティメンバーが地に付した時、その責任は重騎士にあると認識して構わない。<br>自身の役割を努々忘れないことだ。</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 8,
                'name' => '魔導学論I【魔導師】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>【前付】<br>本書を紐解くにあたり、まず留意すべきは「魔導師」という呼称が単なる職業分類を超え、文明史そのものに置いて多義的な位相を占めてきたという事実である。古来より魔導師は単なる呪文行使者に留まらず、しばしば知の伝達者としても...</p><p>(...難しいので、要点をまとめることにした）</p><p>・知力が高く、強力な魔法攻撃が得意。魔法攻撃には耐性あり。<br>・物理攻撃に弱い。序盤はすぐ倒れがちのため周りがサポートしてやろう。<br>・パーティメンバーに回復役がいないなら、ヒーラーとしての役割を担うこともできる。<br>・基本スキル「プチブラスト」を通常攻撃の代わりとして使うと良い。<br>・応用として単体特化、全体特化の魔法攻撃を覚える。状況に応じて覚えていこう。</p><p>(また、とあるスキルについての文面が書かれている）</p><p>近代以降、一部の低俗なる実践者が杖を単なる打撃兵器として振り下ろす行為、すなわち「スタッフスマッシュ」なる滑稽極まる技巧が存在するが、そのような技法を修める者は、知の高みに至ることを自ら放棄し、叡智を暴力の次元に矮小化する存在に他ならない。彼らを魔導師と呼ぶこと自体、いささかの学術的良心を有するものにとっては耐え難い屈辱である。</p><p>（とあるスキルを嫌う内容のようだ... )</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 9,
                'name' => 'レンジャーについて学ぼうI 【弓馭者】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>この本に興味を持ってくれてありがとう！<br>レンジャーは、特殊な効果を持ったスキルを武器とする何でも屋のポジションだよ。</p><p>体力と素早さが高いんだけど、その他の能力も平均的で弱点がないよ。<br>パーティ構成に応じて、自分がアタッカーになるのか、ヒーラーになるのか考えていけるといいね。<br>レベルアップ時にもらえるステータスポイントも、役割に応じて割り振っていこう。<br>色々やろうとすると器用貧乏になっちゃうこともあるから、そこは気をつけて。</p><p>スキルは敵の守備を下げる「ブレイクボウガン」、<br>自分の素早さを強化する「ウインドアクセル」があるよ。<br>どちらも敵にダメージを与えながら追加効果を与えることができるんだ。</p><p>メンバーに回復役が少ないなら「ファーストエイド」を覚えて、<br>持ち前の素早さで治療師の方たちよりも素早いサブヒーラーとしても立ち回れるよ。<br>あ、ファーストエイドは固定の値を回復するスキルだから、<br>回復量に満足がいかなくなってきたらスキルレベル自体を上げる必要があるからね。<br></p><p>パーティ構成に応じて、<br>どのポジションになるのか意識しながら経験を積んでいくといいよ！</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 10,
                'name' => '理（ことわり）の技術I 【理術師】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>...理術師は...自己完結を得意としない支援型...大器晩成の職業...<br>ステータスは凡庸...物理攻撃か魔法攻撃を得意とするか...会得するスキルで決断できる...味方に応じて選択すると良い...</p><p>...理術師の最も得意とする技術...バフスキルにより味方のステータスを高めること...<br>味方の得意技術を伸ばしてやるか...苦手な点を補完してやるか...<br>...序盤のうちはステータスの伸びに実感がないだろうが...じき理解できるはず...</p><p>バフスキルを使うにあたり意識すべき点がある...「使用者自身のステータス」に依存して上昇幅が変化するということだ...味方の攻撃力を高めたいのであれば自身の基礎攻撃力が高いほど効果も上がる...防御力を高めたいのであれば自身の基礎防御力も高いほど効果が上がる...<br>高めたい能力先にステータスポイントを割り振り効力を高める選択も悪くない...</p><p>味方のステータスの上昇値は...理術師であるお前自身に依存する...<br>...意識して...日々研鑽を忘れないことだ...</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 11,
                'name' => 'STRだのDEFだの、なんだそれ？ってヤツへ',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>この本を開いたってことは、初心者と見たぜ。<br>そんなお前に向けて、優しくステータスについて紹介してやるぜ。</p><hr><p>【HP】: Hit Point (体力)<br>0になると戦闘不能になり、その戦闘中では原則戦えなくなるぜ。</p><p>【AP】: Ability Point (AP)<br>スキルを使う時に必要になるポイントだぜ。</p><p>【STR】: Strength (物理攻撃力)<br>この値が高ければ高いほど通常攻撃、物理スキルのダメージが上昇するぜ。</p><p>【DEF】: Defence (物理防御力)<br>この値が高いと、相手から受ける物理防御力のダメージが低下するぜ。<br>多少だが魔法への耐性にも影響するぜ。</p><p>【INT】 : Intelligence (知力)<br>この値が高いと、魔法攻撃の威力が上昇するぜ。<br>また、魔法への耐性もアップするぜ。</p><p>【SPD】 : Speed(素早さ)<br>この値が高いと、戦闘中に行動できる順番が早くなるぜ。<br>また、戦闘から逃走出来る確率もアップするぜ。</p><p>【LUC】 : Luck(運の良さ)<br>この値が高いと、良いことが色々あるらしいぜ。<br>ショップの値段が安くなったり、敵にクリティカルダメージが入りやすくなるって噂だ。</p><hr><p>まあ最初は細かいことは考えず、<br>攻撃しまくるヤツはSTRアップ！魔法を使うヤツはINTアップ！って感じで良いぜ。</p>',
                'required_clears' => 1,
            ],

            // 魔物図譜
            [
                'id' => 101,
                'name' => '魔物図譜: '.FieldData::Grassland->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $grassland_content,
                'required_clears' => 0,
            ],
            [
                'id' => 102,
                'name' => '魔物図譜: '.FieldData::Desert->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $desert_content,
                'required_clears' => 0,
            ],
            [
                'id' => 103,
                'name' => '魔物図譜: '.FieldData::Volcano->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $volcano_content,
                'required_clears' => 0,
            ],
            [
                'id' => 104,
                'name' => '魔物図譜: '.FieldData::Coast->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $coast_content,
                'required_clears' => 0,
            ],

            // 神話歴史学
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

    /**
     * 受け取った敵ID配列を元に、図鑑のHTML要素を生成する
     */
    private function buildEnemyHTMLElement(array $enemyIds, string $preface): string
    {
        $enemies = Enemy::whereIn('id', $enemyIds)
            ->orderByRaw('FIELD(id, '.implode(',', $enemyIds).')') // 並びをEnum順に固定
            ->get();

        $itemsHtml = $enemies->map(fn ($enemy) => $this->renderEnemyListItem($enemy))->implode('');

        return
            <<<HTML
                {$preface}
                <ul class="list-group list-group-flush">
                {$itemsHtml}
                </ul>
            HTML;
    }

    private function renderEnemyListItem(Enemy $enemy): string
    {
        $name = e($enemy->name);
        $hp = e($enemy->value_hp);
        $ap = e($enemy->value_ap);
        $str = e($enemy->value_str);
        $def = e($enemy->value_def);
        $int = e($enemy->value_int);
        $spd = e($enemy->value_spd);
        $luc = e($enemy->value_luc);
        $desc = e($enemy->description);
        $img = "/image/rpg/enemy/{$enemy->portrait_image_path}";

        return
            <<<HTML
                <li class="list-group-item py-3">
                <div class="d-flex">
                    <img src="{$img}" alt="{$name}" loading="lazy"
                        class="rounded flex-shrink-0"
                        style="width:140px;height:100px;object-fit:contain;">
                    <div class="flex-grow-1 ms-3">
                    <div class="font-weight-bold">{$name}</div>
                    <div class="small text-muted text-nowrap">
                        <span>HP: {$hp} |</span>
                        <span>AP: {$ap} |</span>
                        <span>STR: {$str} |</span>
                        <span>DEF: {$def} |</span>
                        <span>INT: {$int} |</span>
                        <span>SPD: {$spd} |</span>
                        <span>LUC: {$luc}</span>
                    </div>
                    <p class="mb-0 mt-1">{$desc}</p>
                    </div>
                </div>
                </li>
            HTML;
    }
}
