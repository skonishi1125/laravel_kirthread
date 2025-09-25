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

        $caution = '<p>※本地域はギルド未調査のため、魔物に関する情報を有していません。</p>';

        // 魔物図譜 草原
        $grassland_preface = '<p>本図譜で述べる草原とは、人間の集落周辺に広がる平坦な地帯そのものを指す。<br>陽光に恵まれ風通しも良く、冒険者にとって最初の修練場として相応しい環境である。<br>魔物の数は少なく、特筆すべき脅威は見られないが、それでも油断は禁物である。<br>とりわけ群れを成す小型種や、俊敏な個体は初心者にとって手強い相手となり得る。<br>ゆえに決して無害を意味しないことは忘れてはならない。</p><p>以下、調査ギルドが保有している魔物情報を記載する。</p>';
        $grassland_content = $this->buildEnemyHTMLElement(
            EnemyData::grasslandAppearingEnemies(),
            $grassland_preface
        );

        // 砂漠
        $desert_preface = '<p>砂漠は、草原から先に広がる広大な乾燥地帯である。昼夜の寒暖差が激しく、地形は単調であるが、潜む魔物は決して侮れない個体が多い。<br>当地に棲息するのは、硬質の鱗を持つリザード類や堅牢な外殻を備えたスコーピオ種など、防御力に優れた個体が多いことで知られる。<br>物理的な攻撃はしばしば弾かれ、長期戦に持ち込まれやすいため、探索に臨む際には魔法攻撃を扱える術者を同行させるのが望ましい。<br>無策に挑めば、無尽の砂よりも先に魔物の甲殻に行く手を阻まれるだろう。</p><p>以下、調査ギルドが保有している魔物情報を記載する。</p>';
        $desert_content = $this->buildEnemyHTMLElement(
            EnemyData::desertAppearingEnemies(),
            $desert_preface
        );

        // 火山
        $volcano_preface = '<p>活発な地殻活動によって形成された本地帯は、熱気と硫黄の噴煙に満ちている。<br>出没する魔物は物理防御力に長けた個体から、知性を持った存在まで多岐にわたり、物理・魔法の両面において脅威を備えている。<br>探索には攻防の均衡が取れたパーティを編成することが肝要である。</p><p>以下、調査ギルドが保有している魔物情報を記載する。</p>';
        $volcano_content = $this->buildEnemyHTMLElement(
            EnemyData::volcanoAppearingEnemies(),
            $volcano_preface
        );

        // 海岸
        $coast_preface = '<p>この地域は、他の地帯に比して知性を帯びた魔物の割合が高いとされる。<br>彼らはしばしばマナを操り魔法攻撃を行使するため、不用意な接近は危険を伴う。<br>物理的な攻撃に対しては脆弱な傾向が見られるため、探索に赴く際には格闘家など、強力な物理攻撃を備えた者を同行させるのが望ましい。<br>油断すれば、穏やかな景観の下に潜む魔物の知略に飲み込まれてしまうだろう。</p><p>以下、調査ギルドが保有している魔物情報を記載する。</p>';
        $coast_content = $this->buildEnemyHTMLElement(
            EnemyData::coastAppearingEnemies(),
            $coast_preface
        );

        // 氷雪地帯
        $iceandsnow_preface = '<p>厳寒の大地に広がるとされる氷雪地帯は、氷結の甲殻や凍りついた筋肉を備え、物理的な攻撃に対して顕著な耐性を示す個体が多いと言われている。<br>氷雪の怪物に挑む際は、環境対策と共に魔法攻撃の準備を怠らぬことが肝要である。白銀の静寂は美しくも、油断する者にとっては永遠の眠りを意味するだろう。</p>';
        $iceandsnow_content = $this->buildEnemyHTMLElement(
            EnemyData::iceAndSnowAppearingEnemies(),
            $iceandsnow_preface
        );

        // 湿霧の地
        $wetfog_preface = '<p>しつむ</p>';
        $wetfog_content = $this->buildEnemyHTMLElement(
            EnemyData::wetFogAppearingEnemies(),
            $wetfog_preface
        );

        // 常夜の森
        $nightforest_preface = '<p>長夜の樹海</p>';
        $nightforest_content = $this->buildEnemyHTMLElement(
            EnemyData::nightForestAppearingEnemies(),
            $nightforest_preface
        );

        // 退廃した耕作地
        $decayedfarmland_preface = '<p>退廃</p>';
        $decayedfarmland_content = $this->buildEnemyHTMLElement(
            EnemyData::decayedFarmlandAppearingEnemies(),
            $decayedfarmland_preface
        );

        // 門前雀羅
        $castletown_preface = '<p>門前雀羅</p>';
        $castletown_content = $this->buildEnemyHTMLElement(
            EnemyData::castleTownAppearingEnemies(),
            $castletown_preface
        );

        // 古城
        $ancientcastle_preface = '<p>古城</p>';
        $ancientcastle_content = $this->buildEnemyHTMLElement(
            EnemyData::ancientCastleAppearingEnemies(),
            $ancientcastle_preface
        );

        // 茫洋の地
        $vastexpanse_preface = '<p>茫洋の地</p>';
        $vastexpanse_content = $this->buildEnemyHTMLElement(
            EnemyData::vastExpanseAppearingEnemies(),
            $vastexpanse_preface
        );

        $seeds = [
            [
                'id' => 1,
                'name' => '冒険の前に「スキル」と「アイテム」！',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>見習い冒険者の皆さん、こんにちは！<br>スララ、みんな簡単に倒しているけどなかなか手強い...<br>そんなことを思っているアナタ。いきなり冒険に出ていませんか？</p><p>準備もなしに冒険に出ること...正直無謀です！<br>冒険には準備がつきもの！<br>特に最序盤の準備の中でも大事なのは、とにかく<b>「スキル」と「アイテム」</b>です！<br></p><p>見習いとはいえ冒険者なら、何かしらの職業に就いているはずですよね。<br>今すぐこの本を読み終えて、ステータス画面を開いてスキルを覚えましょう！<br>次にショップに行って、アイテムを買っておくこともお忘れなく。</p><p>とにかく最初、冒険に出るときは<b>「スキル」と「アイテム」</b>！</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 2,
                'name' => 'ステータスだのスキル振りだの、なんだそれ？ってヤツへ',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>この本を開いたってことは、初心者と見たぜ。<br>そんなお前に向けて、優しくステータスについて紹介してやるぜ。</p><hr><p>【HP】: Hit Point (体力)<br>0になると戦闘不能になり、その戦闘中では原則戦えなくなるぜ。</p><p>【AP】: Ability Point (AP)<br>スキルを使う時に必要になるポイントだぜ。</p><p>【STR】: Strength (物理攻撃力)<br>この値が高ければ高いほど通常攻撃、物理スキルのダメージが上昇するぜ。</p><p>【DEF】: Defence (物理防御力)<br>この値が高いと、相手から受ける物理防御力のダメージが低下するぜ。<br>多少だが魔法への耐性にも影響するぜ。</p><p>【INT】 : Intelligence (知力)<br>この値が高いと、魔法攻撃の威力が上昇するぜ。<br>また、魔法への耐性もアップするぜ。</p><p>【SPD】 : Speed(素早さ)<br>この値が高いと、戦闘中に行動できる順番が早くなるぜ。<br>また、戦闘から逃走出来る確率もアップするぜ。</p><p>【LUC】 : Luck(運の良さ)<br>この値が高いと、良いことが色々あるらしいぜ。<br>ショップの値段が安くなったり、敵にクリティカルダメージが入りやすくなるって噂だ。</p><hr><p>ステータス振りについて悩むかもしれねえが、<br>まあ最初は細かいことは考えず、攻撃しまくるヤツはSTRアップ！<br>魔法を使うヤツはINTアップ！って感じで良いぜ。</p><p>ちなみにスキルも一緒だ。<br>最初のうちはSTRが高いやつは物理スキル！<br>INTが高いやつは魔法スキル！って感じでどんどん振っていくといいぜ。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 3,
                'name' => '優しく解説！戦闘術〜プチ回復編〜',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>初学者の方に向けて、戦闘の技術について優しく解説します。<br>ずっと使える知識なので、覚えておくとためになりますよ。</p><p>街からフィールドに出ると、モンスターとの戦いが連続して発生する形となります。<br>モンスターを全員倒すとEXP(経験値)が貰えますが、同時にHPとAPも少量回復します。<br>また、レベルが上がるとHPとAPが全回復します！</p><p>つまり戦闘では毎回APを使ってしまっても問題は無いですし、<br>レベルアップ手前のメンバーがいた場合は、戦闘でガンガンAPを使ってしまって良いということです！<br>次のレベルアップまでのEXPはステータス画面や戦闘終了画面で確認ができますよ。</p><p>次回は逃亡時、全滅時について解説します。</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 4,
                'name' => '優しく解説！戦闘術〜逃亡・全滅編〜',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>初学者の方に向けて、戦闘の技術について優しく解説します。<br>今回は逃走時、全滅時の違いについてです。<br>あまり考えたいことではありませんが、もしもの時に役立ちますよ。</p><p></p><p>逃走した時は、「使ったアイテム」、「今まで獲得したEXPやレベル」が現時点の状態で街に戻ることになります。探索の中断と同じですね。<br>また全滅してしまった時は、「使ったアイテム」、「今まで獲得したEXPやレベル」が冒険前の状態にリセットされた状態で街に戻ることになります。<br></p><p>全滅は大変なことのように見えますが、アイテムをたくさん使ってもフィールドの探索が失敗した時にアイテムを消費してしまうことを防いでくれます。</p><p>反省して、もう一度トライしてみましょう！</p>',
                'required_clears' => 1,
            ],

            // 職能編纂
            [
                'id' => 101,
                'name' => '格闘家の在り方I【格闘家】',
                'book_category' => Library::CATEGORY_JOB,
                'content' => '<p>格闘家は高い物理火力が売りの職業だ！<br>味方の構成は気にせず、とにかく必要だと思うスキルを会得していけば良いだろう！</p><p>火力と同じほど自信があるのが素早さだ！<br>敵味方の中で先手を取ることが殆どだろう！アイテムを持っているなら速さを活かして味方を支援するのも素晴らしい！</p><p>HPも高めではあるが、軽装しか基本的に纏えないため耐久力はさほどない！<br>また魔法への耐性も皆無と言って良いだろう！自身の打たれ強さを過信しないことだ！<br>いわゆるAPも充分に持ち合わせていない！<br>長時間の戦闘が不安ならばポイントを割り振り補強するのも悪くないだろう！</p><p>悲観的なことも書いてしまったが、<br>高速火力というだけでお釣りが来るほど役割を果たすことができる！<br>格闘家という職業に誇りを持って戦えることを願う！</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 102,
                'name' => '癒し手としてI【治療師】',
                'book_category' => Library::CATEGORY_JOB,
                'content' => '<p>治療師は聖なる力で味方を助ける、支援型の職業です。</p><p>まずは回復系のスキルを会得するよう励みましょう。<br>基礎とも言える単体回復スキルは他の職業も会得できますが、経験を積めば豊富な回復手段を覚えることができます。</p><p>魔法攻撃スキルは、パーティ全体のバランスを見て要否を判断すると良いでしょう。<br>中には攻撃魔法を中心に鍛錬される方もいらっしゃるようですが。</p><p>物理攻撃は基本的に不向きです。<br>耐久力は無いことはないのですが、良いわけでもありませんので過信は禁物です。</p><p>レベルが上がることで得られるステータスポイントについては、<br>強みであるINTや、状況に応じて素早さに振り分けていくのも良いですね。</p><p>治療師として大事なことは、仲間を戦闘不能にさせないように立ち回ることです。<br>本書の内容を理解すれば、意識出来るようになりますよ。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 103,
                'name' => '騎士の担う役割I【重騎士】',
                'book_category' => Library::CATEGORY_JOB,
                'content' => '<p>重騎士は秀でた耐久力で味方の壁となる、守りの職業である。</p><p>重騎士の基礎であり、象徴とも言える「ワイドガード」は唯一無二の性能を持つ。<br>打たれ弱い味方が居る場合は重宝すること間違い無いだろう。<br>ただし自分の経験が少ないままスキルレベルを上げ過ぎてしまうと、<br>AP切れが発生しやすくなり、大事な場面で使えなくなるケースには気をつけることだ。</p><p>「ワイドガード」「ガードアップ」の効果は自身の防御力に依存して上昇する。<br>優秀な防御力をさらにポイントを割り振り高めていっても構わない。</p><p>物理火力に自信のないパーティ構成なら、アタッカーとして立ち回っても良いだろう。<br>行動速度は遅いが、安定した火力を出せるようになるはずだ。</p><p>パーティメンバーが地に付した時、その責任は重騎士にあると認識して構わない。<br>自身の役割を努々忘れないことだ。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 104,
                'name' => '魔導学論I【魔導師】',
                'book_category' => Library::CATEGORY_JOB,
                'content' => '<p>【前付】<br>本書を紐解くにあたり、まず留意すべきは「魔導師」という呼称が単なる職業分類を超え、文明史そのものに置いて多義的な位相を占めてきたという事実である。古来より魔導師は単なる呪文行使者に留まらず、しばしば知の伝達者としても...</p><p>(...難しいので、要点をまとめることにした）</p><p>・知力が高く、強力な魔法攻撃が得意。魔法攻撃には耐性あり。<br>・物理攻撃に弱い。序盤はすぐ倒れがちのため周りがサポートしてやろう。<br>・パーティメンバーに回復役がいないなら、ヒーラーとしての役割を担うこともできる。<br>・基本スキル「プチボルト」を通常攻撃の代わりとして使うと良い。<br>・応用として単体特化、全体特化の魔法攻撃を覚える。状況に応じて覚えていこう。</p><p>(また、とあるスキルについての文面が書かれている）</p><p>近代以降、一部の低俗なる実践者が杖を単なる打撃兵器として振り下ろす行為、すなわち「スタッフスマッシュ」なる滑稽極まる技巧が存在するが、そのような技法を修める者は、知の高みに至ることを自ら放棄し、叡智を暴力の次元に矮小化する存在に他ならない。彼らを魔導師と呼ぶこと自体、いささかの学術的良心を有するものにとっては耐え難い屈辱である。</p><p>（とあるスキルを嫌う内容のようだ... )</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 105,
                'name' => 'レンジャーについて学ぼうI 【弓馭者】',
                'book_category' => Library::CATEGORY_JOB,
                'content' => '<p>この本に興味を持ってくれてありがとう！<br>レンジャーは、特殊な効果を持ったスキルを武器とする何でも屋のポジションだよ。</p><p>体力と素早さが高いんだけど、その他の能力も平均的で弱点がないよ。<br>パーティ構成に応じて、自分がアタッカーになるのか、ヒーラーになるのか考えていけるといいね。<br>レベルアップ時にもらえるステータスポイントも、役割に応じて割り振っていこう。<br>色々やろうとすると器用貧乏になっちゃうこともあるから、そこは気をつけて。</p><p>スキルは敵の守備を下げる「ブレイクボウガン」、<br>自分の素早さを強化する「ウインドアクセル」があるよ。<br>どちらも敵にダメージを与えながら追加効果を与えることができるんだ。</p><p>メンバーに回復役が少ないなら「ファーストエイド」を覚えて、<br>持ち前の素早さで治療師の方たちよりも素早いサブヒーラーとしても立ち回れるよ。<br>あ、ファーストエイドは固定の値を回復するスキルだから、<br>回復量に満足がいかなくなってきたらスキルレベル自体を上げる必要があるからね。<br></p><p>パーティ構成に応じて、<br>どのポジションになるのか意識しながら経験を積んでいくといいよ！</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 106,
                'name' => '理（ことわり）の技術I 【理術師】',
                'book_category' => Library::CATEGORY_JOB,
                'content' => '<p>...理術師は...自己完結を得意としない支援型...大器晩成の職業...<br>ステータスは凡庸...物理攻撃か魔法攻撃を得意とするか...会得するスキルで決断できる...味方に応じて選択すると良い...</p><p>...理術師の最も得意とする技術...バフスキルにより味方のステータスを高めること...<br>味方の得意技術を伸ばしてやるか...苦手な点を補完してやるか...<br>...序盤のうちはステータスの伸びに実感がないだろうが...じき理解できるはず...</p><p>バフスキルを使うにあたり意識すべき点がある...「使用者自身のステータス」に依存して上昇幅が変化するということだ...味方の攻撃力を高めたいのであれば自身の基礎攻撃力が高いほど効果も上がる...防御力を高めたいのであれば自身の基礎防御力も高いほど効果が上がる...<br>高めたい能力先にステータスポイントを割り振り効力を高める選択も悪くない...</p><p>味方のステータスの上昇値は...理術師であるお前自身に依存する...<br>...意識して...日々研鑽を忘れないことだ...</p>',
                'required_clears' => 0,
            ],

            // 魔物図譜
            [
                'id' => 201,
                'name' => '魔物図譜: '.FieldData::Grassland->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $grassland_content,
                'required_clears' => 0,
            ],
            [
                'id' => 202,
                'name' => '魔物図譜: '.FieldData::Desert->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $desert_content,
                'required_clears' => 1,
            ],
            [
                'id' => 203,
                'name' => '魔物図譜: '.FieldData::Volcano->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $volcano_content,
                'required_clears' => 1,
            ],
            [
                'id' => 204,
                'name' => '魔物図譜: '.FieldData::Coast->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $coast_content,
                'required_clears' => 1,
            ],
            [
                'id' => 205,
                'name' => '(未完成)魔物図譜: '.FieldData::IceAndSnow->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $iceandsnow_preface.$caution,
                'required_clears' => 3,
            ],
            [
                'id' => 206,
                'name' => '魔物図譜: '.FieldData::IceAndSnow->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $iceandsnow_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::IceAndSnow,
            ],
            [
                'id' => 207,
                'name' => '(未完成)魔物図譜: '.FieldData::WetFog->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $wetfog_preface.$caution,
                'required_clears' => 3,
            ],
            [
                'id' => 208,
                'name' => '魔物図譜: '.FieldData::WetFog->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $wetfog_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::WetFog,
            ],
            [
                'id' => 209,
                'name' => '(未完成)魔物図譜: '.FieldData::NightForest->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $nightforest_preface.$caution,
                'required_clears' => 3,
            ],
            [
                'id' => 210,
                'name' => '魔物図譜: '.FieldData::NightForest->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $nightforest_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::NightForest,
            ],
            [
                'id' => 211,
                'name' => '(未完成)魔物図譜: '.FieldData::DecayedFarmland->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $decayedfarmland_preface.$caution,
                'required_clears' => 5,
            ],
            [
                'id' => 212,
                'name' => '魔物図譜: '.FieldData::DecayedFarmland->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $decayedfarmland_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::DecayedFarmland,
            ],
            [
                'id' => 213,
                'name' => '(未完成)魔物図譜: '.FieldData::CastleTown->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $castletown_preface.$caution,
                'required_clears' => 5,
            ],
            [
                'id' => 214,
                'name' => '魔物図譜: '.FieldData::CastleTown->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $castletown_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::CastleTown,
            ],
            [
                'id' => 215,
                'name' => '(未完成)魔物図譜: '.FieldData::AncientCastle->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $ancientcastle_preface.$caution,
                'required_clears' => 5,
            ],
            [
                'id' => 216,
                'name' => '魔物図譜: '.FieldData::AncientCastle->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $ancientcastle_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::AncientCastle,
            ],
            [
                'id' => 217,
                'name' => '(未完成)魔物図譜: '.FieldData::VastExpanse->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $vastexpanse_preface.$caution,
                'required_clears' => 5,
            ],
            [
                'id' => 218,
                'name' => '魔物図譜: '.FieldData::VastExpanse->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $vastexpanse_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::VastExpanse,
            ],

            // 神話歴史学
            [
                'id' => 301,
                'name' => '民話I',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>かつて、魔物が蔓延る大陸があった。<br>そこは噴煙を噴き上げる火山、凍てつく氷雪の地、陽光の届かぬ闇の領域。苛烈なる自然は、魔物の気配に呼応するかのごとくだった。<br>とある日、その地を無名の冒険者たちが踏みしめることとなる。</p><p>この伝承は、まさしくその瞬間より幕を開けたのであった。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 302,
                'name' => '民話II',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>この大陸には、比類なき力を誇る魔物の住まう集落があるとされている。<br>個別での踏破は難しいと考えた冒険者たちは、三人一組の徒党を結成した。</p><p>拳を振るう者、魔術を操る者、そして仲間を支える者。<br>彼らはまず小さな拠点を築き、そこを起点として旅路を始めた。<br>やがて草原を抜け、灼熱の火山を越え、深き沼地を踏み分けるーー。<br></p><p>ついに彼らの眼前に現れたのは、人の手で築かれたとは到底思えぬ石の城だった。</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 303,
                'name' => '民話III',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>「ここは、大陸に蔓延る魔物の根城に違いない。」<br>冒険者たちは城へ乗り込み、数多の魔物の群れへと立ち向かう。<br>邪悪を退け、ついには、城を支配する主をも討ち果たした。<br></p><p>この偉業は大陸中に響き渡り、人々は彼らを英雄として崇め讃えた。</p><p>魔物の残党は退き、そして城の周囲には人が集い、富と秩序が築かれた。<br>かつて魔物の根城と恐れられた地周辺は、大陸随一の都へと変貌した。</p><p>こうして大陸は新たな時代を迎えたのであった。</p>',
                'required_clears' => 3,
            ],
            [
                'id' => 304,
                'name' => '民話IV',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>繁栄の時代は幾十年にも及び、都は大陸の中心として栄華を誇った。</p><p>だがある日、英雄たちは誰に知られることもなく、忽然と姿を消した。<br>その理由を知る者はなく、人々はただ静かな不安を胸に抱えたという。<br></p><p>やがてその懸念は現実となり、忘れられたはずの魔物が再び大陸を侵した。<br>英雄たち無き世代では抗うことはできず、城は再び魔の手へと堕ちていった。<br>かくして繁栄の時代は幕を閉じ、大陸は再び魔物が蔓延ることとなったのである。<br>ただひとつ、今もなお語り継がれる言葉がある。</p><p>"かつて王国を築きし英雄の財宝は、未だ古城の奥深くに眠っているーー。"</p><p>この言葉は、人々を再び魔物に立ち向かわせるには充分な理由となった。<br></p><p>そしてこの時代は、今日へと続いているのである。</p>',
                'required_clears' => 3,
            ],
            [
                'id' => 305,
                'name' => '書き殴られた本I',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>（...棚の隅に、本が挟み込まれている。図書館で管轄されていない書物だ。）</p><span style="color:red"><p>古き書物は声高に語る。<br>「魔物は人を脅かし、英雄はそれを討った」と。<br>しかし、私はその記述に疑念を抱かずにはいられぬ。</p><p>果たして、魔物が人の暮らしをどれほど脅かしていたというのか。<br>火を吹く竜が村を焼いた記録も、氷の魔物が街を凍らせた記録もどこにも残されてはいない。<br>書物の多くは「討伐」という結末のみを声高に語り、始まりを曖昧にしている。<br>まるで誰かが都合のよい物語を編んだかのようではないか。<br></p><p>むしろ魔物は、人を避けるように山に籠り、沼に沈み、光の届かぬ地に棲んでいたのでないだろうか。<br>彼らが本当に人と敵対しているのであれば、なぜ今日までこの街は平穏に保たれているのか。<br></p><p>英雄たちが武器を振るった理由は何か。<br>正義か、使命か、それとも……ただ己が力を誇示したかっただけなのか。<br></p><p>「伝承」と呼ばれるものは、果たして真実か。<br>私はそうは思わない。</p></span>',
                'required_clears' => 7,
            ],
            [
                'id' => Library::VAST_EXPANSE_FLAG_BOOK_ID,
                'name' => '書き殴られた本II',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>（...棚の隅に、本が挟み込まれている。図書館で管轄されていない書物だ。）</p><span style="color: red"><p>かつての英雄のように、ついに古城を踏破する冒険者が現れた。<br>その報せに街は沸き立ち、ギルドも徐々に地形の調査を進めている。<br>ただし、愚かにも奴らの関心は上層階に積まれた財宝にばかり向けられ、周辺の探索など意にも介していない。<br></p><p>私はひとり、城から少し離れた寂れた耕作地を調べてみた。<br>崩れ落ちた瓦礫の下に、地下へと続く階段を見つけたのである。<br>降りてゆくと、そこは牢獄として使われていたらしく、壁や床には乾ききった血痕のような汚れが点々と残っていた。<br>その陰惨たる場所の中央に薄青く輝く不気味な光。<br>まるで異界へ通じるかのようなポータルが存在していたのだ。<br></p><p>このポータルは、伝承の欺瞞を解き明かす手掛かりになるに違いない。<br>ギルドの連中が夢中になっている金銀財宝など、真実に比べれば塵芥に等しい。</p><p>私はこのポータルに入ることを決めた。この足で、歴史の虚飾を打ち砕くのだ。</p></span>',
                'required_clear_field_id' => FieldData::AncientCastle,
            ],
            [
                'id' => 307,
                'name' => '不気味な日記',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>（茫洋の地で拾った、日記帳だ。）</p><span style="color: red"><p>【一日目】<br>ポータルを潜ると、そこは地平が続く虚無の大地であった。<br>恐ろしく広大で、振り返っても先ほど入ったはずの門は影も形もない。<br>どうやら先に進み、出口を探すしかないようだ。<br>私は今、求め続けていた正史を開拓している。武者震いが止まらない。<br></p><p>【三日目】<br>違和感に気づいた。<br>寝食を忘れるほど調査に没頭していたことは認めるが、言葉通りこの世界に入ってから腹の虫は鳴かず、喉の渇きすら感じない。<br>どうやら魔力が大気に満ちており、活動に必要なエネルギーを自然に取り込むことができているようだ。<br></p><p>【五日目】<br>生命体を発見した。<br>遠目でしか見ていないが、それは魔物とは似ても似つかぬ、おぞましい生命体だった。<br>是非とも観察したいと心が疼いたが、絶対的な直感が近づくなと叫んだ。</p><p>【七日目】<br>恐ろしいこと、そして奇妙なことが起きた。<br>私は例の生命体と鉢合わせ、襲われた。<br>全ての終わりを覚悟したその刹那、私を救ったのは魔物であった。<br>それは人間である私よりか弱き存在に見えたが、驚くほどの俊敏さを持ち、私を背中に乗せて退避してくれた。<br>親切心を持ち合わせているのか、その魔物は無の世界の片隅にあった光るポータルまで私を運んでくれ、そして去っていった。<br></p><p>ポータルには奇妙な呪文が施されていた。特定の存在のみを拒み通さぬよう細工されている。<br>ここは、あの生命体を封じ込める監獄なのではないだろうか。</p><p>この文章を書いている今も、襲われた事実に震えが止まらず、正直帰りたい。<br>ただし、自分の探究心がそれを許してはくれない。</p><p>あの生命体の正体は？<br>自分を救ってくれた魔物の種は？</p><p>出口を横目に、私は探索を辞めぬ事を決意する。<br>あの生命体の謎を、必ず解き明かしてみせようではないか。</p><p>【？日目】<br>ついに、例の生命体の観察に成功した。<br>腕は六本にも及び、驚くべき身体能力を誇るのみならず、魔法すら自在に操る様子を見せた。<br>魔物が特殊な能力を持つことはあるが、あれほど多様な技を併せ持つ存在は聞いたことがない。<br></p><p>そして私は決定的な違和感を覚えた。<br>生命体が操っていたものは、間違いなく冒険者たちの「スキル」そのものだった。<br>まさか、この生命体はかつて__<br></p></span><br><br><p>（黒ずんだ滲みとともに、文章はここで途切れている...。）</p>',
                'required_clear_field_id' => FieldData::VastExpanse,
            ],
            [
                'id' => 397,
                'name' => '草原クリア後に出る本',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>草原</p>',
                'required_clear_field_id' => FieldData::Grassland,
            ],
            [
                'id' => 398,
                'name' => '砂クリア後に出る本',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>砂漠</p>',
                'required_clear_field_id' => FieldData::Desert,
            ],
            [
                'id' => 399,
                'name' => '氷雪地帯クリア後に出る本',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>氷雪</p>',
                'required_clear_field_id' => FieldData::IceAndSnow,
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
