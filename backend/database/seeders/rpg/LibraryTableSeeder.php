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

        $caution = '<p><b>本地域は調査ギルド未開拓のため、充分な魔物情報を有しておりません。<br>探索の成功した冒険者様がたのご報告を心よりお待ちしております。</b></p>';

        // 魔物図譜 草原
        $grassland_preface = '<p>本図譜で述べる草原とは、我々の生活する街周辺に広がる平坦な地帯そのものを指す。<br>陽光に恵まれ風通しも良く、冒険者にとって最初の修練場として相応しい環境である。<br>魔物の数は少なく、特筆すべき脅威は見られないが、それでも油断は禁物である。<br>とりわけ群れを成す小型種や、俊敏な個体は初心者にとって手強い相手となり得る。<br>ゆえに決して無害を意味しないことは忘れてはならない。</p><p>以下、調査ギルドが保有している魔物情報を記載する。</p>';
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
        $coast_preface = '<p>この地域は、他の地帯に比して知性を帯びた魔物の割合が高いとされる。<br>彼らはしばしば魔法攻撃を行使するため、不用意な接近は危険を伴う。<br>物理的な攻撃に対しては脆弱な傾向が見られるため、探索に赴く際には格闘家など、強力な物理攻撃を備えた者を同行させるのが望ましい。<br>油断すれば、穏やかな景観の下に潜む魔物の知略に飲み込まれてしまうだろう。</p><p>以下、調査ギルドが保有している魔物情報を記載する。</p>';
        $coast_content = $this->buildEnemyHTMLElement(
            EnemyData::coastAppearingEnemies(),
            $coast_preface
        );

        // 湿霧の地
        $wetfog_preface = '<p>絶えず湿気を含んだ霧に覆われる本地帯は、視界が不明瞭であり冒険者の行動を鈍らせる不快な環境として知られる。当地には多種多様な植物型の魔物が繁茂し、根や蔓を操って侵入者を絡め取り力を削ぐ技に長けている。獲物を弱体させたのち、じわじわと仕留める戦法を常とするため油断は命取りである。</p><p>物理攻撃と魔法攻撃を兼ね備えた均衡の取れた編成で挑むことが推奨されるが、それでもなお危険は尽きない。古くからの記録には、濃霧の奥に棲まう巨影の存在が記されている。</p><p>真偽は定かでないが、挑む者は覚悟が必要である。</p>';
        $wetfog_content = $this->buildEnemyHTMLElement(
            EnemyData::wetFogAppearingEnemies(),
            $wetfog_preface
        );

        // 氷雪地帯
        $iceandsnow_preface = '<p>厳寒の大地に広がるとされる氷雪地帯は鍛え上げられた肉体を備え、<br>物理的な攻撃に対して耐性を示す個体が多いと言われている。<br>氷雪の怪物に挑む際は、魔法攻撃の準備を怠らぬことが肝要である。</p><p>この地帯にはペンギン種の魔物が多く生息しており、<br>お腹を整える一定のしぐさをしたのちに突進してくる習性を持つ。</p><p>また、時折見かける青白い妖精の放つ全体攻撃は非常に強力である。打ち込まれる回数には限りがあるようなので、暫くの間防御でやり過ごすことが有効か。</p>';
        $iceandsnow_content = $this->buildEnemyHTMLElement(
            EnemyData::iceAndSnowAppearingEnemies(),
            $iceandsnow_preface
        );

        // 常夜の森
        $nightforest_preface = '<p>夜が支配するこの地帯は陽光が差し込むことは一切なく、あらゆる時刻が闇に包まれている。唯一の光源となるのは、群生するホシホタルと呼ばれる生物であり、その微光によって生物たちはわずかな視界を得ることが可能である。森に棲息する魔物の多くは高い知能を有しており、魔法による攻撃は効果が薄いということが報告されている。したがって、探索には物理的な火力が不可欠となるだろう。</p><p>一方で、この地にも僅かな安らぎが存在する。ホシホタルたちが飛翔するとき、漆黒の天に光が瞬き、銀河を想わせる幻想的な光景が現れるという。この美景は、本地帯の脅威を承知の上でなお足を運ぶ者を惹きつけてやまない。</p>';
        $nightforest_content = $this->buildEnemyHTMLElement(
            EnemyData::nightForestAppearingEnemies(),
            $nightforest_preface
        );

        // 退廃した耕作地
        $decayedfarmland_preface = '<p>本地帯は城下町の近郊に位置する農耕地と思われる。寂れた風景は栄華の終焉を映すかのようであり、当時の繁栄をかすかに偲ばせる。魔物自体はそこまで多くの種は見かけられない。本地には朽ちかけたカカシが自らの意思を持つかのように彷徨っており、カカシによって冒険者が負傷したという報告がギルドに複数寄せられている。十分な実力を備えていない冒険者は本地帯へ安易に立ち入らぬ方が賢明だろう。乾いた藁の匂いが荒れた風に乗って漂うが、ごく稀に血と腐敗の混じった異臭がそれに紛れて感じられることがあるという。</p>';
        $decayedfarmland_content = $this->buildEnemyHTMLElement(
            EnemyData::decayedFarmlandAppearingEnemies(),
            $decayedfarmland_preface
        );

        // 門前雀羅
        $castletown_preface = '<p>本地帯は、かつて英雄たちが繁栄を築いた城下町の成れの果てと見られる。割れた石畳、傾いだ家屋など辺り全てが過日の栄華の終わりを物語る。出現する魔物はいずれも強力であり、特に耐久力の高い魔物が多いため戦闘は自然と長期戦になりやすい。スキルの多用によりAPの消耗が懸念されるため、回復薬等のアイテムは多めに携行しておくと良いだろう。なお外縁部および城門跡周辺では巨大なゴーレムが球状ユニットを従えて徘徊する姿が報告されている。ユニットは極めて頑強で、正面からの破壊は推奨されない。ユニットの持つエネルギーには限りがあるようなので、エネルギー切れを待つのが賢明だろう。</p>';
        $castletown_content = $this->buildEnemyHTMLElement(
            EnemyData::castleTownAppearingEnemies(),
            $castletown_preface
        );

        // 古城
        $ancientcastle_preface = '<p>この古き城は、元来魔物が築いたものである。伝承に語られる英雄たちがこれを制圧し拠点として利用したが、現在は再び魔の手に渡っている。出現する魔物が精鋭揃いであることは言うまでもない。己の経験を活かし、魔物の特徴を掴みつつ探索を進める必要があるだろう。遥か上層からは時折龍が何者かと対話しているかのような呻き声が響き渡る。</p>';

        $ancientcastle_content = $this->buildEnemyHTMLElement(
            EnemyData::ancientCastleAppearingEnemies(),
            $ancientcastle_preface
        );

        // 古城の祭壇
        $ancientcastlealtar_preface = '<p>(自分たちが調査ギルドに報告しようと考えている内容を記した手記だ。)</p><div style="color: darkslategray;"><p>古城上層の最奥には、脈打つ気配の潜んだ部屋があった。中には銅像が祀られ、祭壇としての体裁が備えられていた。崩れた他区画と比べて異様に整えられていることから、城が人の手に渡っていた時期に改修された部屋と思われる。目撃した生命体には罰せられた痕が残っており、生々しい傷跡は罪の重さを彷彿とさせる。</p></div>';
        $ancientcastlealtar_content = $this->buildEnemyHTMLElement(
            EnemyData::ancientCastleAltarAppearingEnemies(),
            $ancientcastlealtar_preface
        );

        // 茫洋の地
        $vastexpanse_preface = '<p>(自分たちが調査ギルドに報告しようと考えている内容を記した手記だ。)</p><div style="color: darkslategray;"><p>耕作地の地下、薄青く輝くポータルを抜けた先の空間。自然の気配は一切なく、空も地も境を失ったように淡く光を帯びており、現世から切り離された別界のようであった。</p><p>この空間に出現する魔物は一様ではない。強力な個体が多数確認されたが、なかには我々に敵意を示さぬ存在もいた。むしろ、傷を癒し、道を示すような行動を取るものすらいた。彼らは明らかに人間と魔物の対立という理から外れた、特異な性質を持つ魔物である。</p><p>また、この空間で人とも魔物とも形容し難い存在を確認した。その力は圧倒的で、もし我々の住む外界に存在していたなら、この大陸そのものを制圧できるほどの力だっただろう。幸いにも、我々はそれを打ち倒すことができた。</p></div><p>(――手記を記した直後、僅かな違和感を感じたことを思い出した。<br>あれほどの存在を討ち倒した自分たちの力そのものに、異質さを感じたのだ。<br>伝承に謳われる財宝を手にすることで得た富、当然付いてくるであろう名声。<br>そして圧倒的な力を備えた今ならば、本当にどんな願いも叶えられるかもしれない。)</p>';
        $vastexpanse_content = $this->buildEnemyHTMLElement(
            EnemyData::vastExpanseAppearingEnemies(),
            $vastexpanse_preface
        );

        $seeds = [
            [
                'id' => 1,
                'name' => '冒険の前に「スキル」と「アイテム」！【月刊トリップ】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>月刊トリップです！<br>今月号は、駆け出し冒険者の皆さんに向けた記事です。</p><p>スララ、みんな簡単に倒しているけどなかなか手強い...<br>そんなことを思っているアナタ。いきなり冒険に出ていませんか？</p><p>準備もなしに冒険に出ること...正直無謀です！<br>冒険には準備がつきもの！<br>特に最序盤の準備の中でも大事なのは、とにかく<b>「スキル」と「アイテム」</b>です！<br></p><p>見習いとはいえ冒険者なら、何かしらの職業に就いているはずですよね。<br>今すぐこの本を読み終えて、ステータス画面を開いてスキルを覚えましょう！<br>次にショップに行って、アイテムを買っておくこともお忘れなく。</p><p>とにかく最初、冒険に出るときは<b>「スキル」と「アイテム」</b>！</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 2,
                'name' => 'ステータスだのスキル振りだの、なんだそれ？ってヤツへ',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>この本を開いたってことは、初心者と見たぜ。<br>そんなお前に向けて、優しくステータスについて紹介してやるぜ。</p><hr><p>【HP】: Hit Point (体力)<br>0になると戦闘不能になり、その戦闘中では原則戦えなくなるぜ。</p><p>【AP】: Ability Point (AP)<br>スキルを使う時に必要になるポイントだぜ。</p><p>【STR】: Strength (物理攻撃力)<br>この値が高ければ高いほど通常攻撃、物理スキルのダメージが上昇するぜ。</p><p>【DEF】: Defence (物理防御力)<br>この値が高いと、相手から受ける物理防御力のダメージが低下するぜ。<br>多少だが魔法への耐性にも影響するぜ。</p><p>【INT】 : Intelligence (知力)<br>この値が高いと、魔法攻撃の威力が上昇するぜ。<br>また、魔法への耐性もアップするぜ。</p><p>【SPD】 : Speed(素早さ)<br>この値が高いと、戦闘中に行動できる順番が早くなるぜ。<br>また、戦闘から逃走出来る確率もアップするぜ。</p><p>【LUC】 : Luck(運の良さ)<br>この値が高いと、良いことが色々あるらしいぜ。<br>ショップの値段が安くなったり、敵にクリティカルダメージが入りやすくなるって噂だ。</p><hr><p>さてと、次にステータス・スキル振りについてだ。<br>レベルが上がるとステータスポイントが4ポイント、レベルが2の倍数の時にはスキルポイントが1ポイント貰えるぜ。それぞれ自由に振り分けられるぶん悩むかもしれねえが、まあ最初は細かいことは考えず、<b>攻撃しまくるヤツはSTRアップ！魔法を使うヤツはINTアップ！</b>って感じで良いぜ。</p><p>スキル振りも同じ考えだ。最初のうちは<b>STRが高いやつは物理スキル！INTが高いやつは魔法スキル！</b>って感じでどんどん振っていくといいぜ。どうせポイントはこれからいっぱい貰えるんだからな。</p><p>いや、待てよ。でも格闘家なんかはすぐガス欠しちまうからAPに振り分けるのもアリだよな。それで言うと、魔導師がHPに振り分けるのもアリか。つ〜ことはだな...</p><p>(ぶつぶつと取り止めの無い内容が続いている...)</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 3,
                'name' => '優しく解説！戦闘術〜プチ回復編〜',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>初学者の方に向けて、戦闘の技術について優しく解説します。<br>ずっと使える知識なので、覚えておくとためになりますよ。</p><p>街からフィールドに出ると、モンスターとの戦いが連続して発生する形となります。<br>モンスターを全員倒すとEXP(経験値)が貰えますが、<b>同時にHPとAPも少量回復</b>します。<br><b>また、レベルが上がるとHPとAPが全回復します！</b></p><p>つまり戦闘では毎回APを使ってしまっても問題は無いですし、<br>レベルアップ手前のメンバーがいた場合は、戦闘でガンガンAPを使ってしまって良いということです！<br>次のレベルアップまでのEXPはステータス画面や戦闘終了画面で確認ができますよ。</p><p>次回は逃亡時、全滅時について解説します。</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 4,
                'name' => '優しく解説！戦闘術〜逃亡・全滅編〜',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>初学者の方に向けて、戦闘の技術について優しく解説します。<br>今回は逃走時、全滅時の違いについてです。<br>あまり考えたいことではありませんが、もしもの時に役立ちますよ。</p><p></p><p>逃走した時は、<b>「使ったアイテム」、<br>「今まで獲得したEXPやレベル」が現時点の状態</b>で街に戻ることになります。探索の中断と同じですね。<br>また全滅してしまった時は、<b>「使ったアイテム」、<br>「今まで獲得したEXPやレベル」が冒険前の状態にリセットされた状態</b>で街に戻ることになります。<br></p><p>全滅は大変なことのように見えますが、アイテムをたくさん使ってもフィールドの探索が失敗した時にアイテムを消費してしまうことを防いでくれます。</p><p>反省して、もう一度トライしてみましょう！</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 5,
                'name' => 'クリティカル・致命の一撃について',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>砂漠地帯に棲息するサソリ型の魔物は、しばしば<b>致命の一撃</b>を放つことで知られている。<br>冒険者側の言葉で言うと、所謂<b>クリティカル</b>攻撃である。</p><p>致命の一撃は<b>DEFを完全に無視する特性</b>を持つ。ゆえに、重装備を誇る重騎士であっても油断すれば深手を負うことになる。またDEFを高める類の魔法スキルもこの一撃の前では無力だ。耐久力に乏しい職種の者が直撃を受ければひとたまりもないだろう。</p><p>しかしながら、この力は敵のみが持つものではなく、冒険者の攻撃にもクリティカル攻撃が発生することがある。成功すれば魔物の防御を貫き、深々と刃を突き立てることができるだろう。</p><p>狙って放つことは難しいが、運命が味方する瞬間にこそ戦況を覆す一撃が生まれる。その一閃に賭けてみるのも、また冒険者の醍醐味である。</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 6,
                'name' => '固定行動をもつ魔物【月刊トリップ】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>月刊トリップだ。</p><p><span style="color:blue">体力が青く表示されている魔物</span>を見かけたことはないか？<br>そいつは特定の習性を持つ、「固定行動」を持った魔物だ。</p><p>代表的なのはワニ系統の敵だ。<br>必ず戦闘開始時に「咆哮」をあげて、自身のステータスを高めてくるんだ。<br>その間にボコっちまうかもしくは戦闘体制を整えるかはあんたの自由だ。</p><p>ワニ系統以外にも特定の習性を持つ魔物はたくさんいるから、<br>体力が青く表示される敵を見かけた時は<b>ターン数と行動</b>を気に留めておくといいぜ。</p>',
                'required_clears' => 2,
            ],
            [
                'id' => 7,
                'name' => '戦闘メモのススメ',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>戦闘時に表示される「戦闘メモ」は使っているか？</p><p>あれはただの飾りじゃない。戦闘ログを見ながら、気づいたことや気になる動きを書き留めておくものだ。例えば未開拓の土地を探索するとき、その土地の主までの歩数や出てくる魔物の種類、行動の癖なんかをメモしておけば次に挑むときに大いに役立つ。</p><p>「そんなの覚えてられる」と言う奴もいるが、戦場の記憶なんて案外あやふやなものだ。</p><p>まだ使ったことがないなら、まずは殴り書きで構わない。<br>ちょっとした思いつきでもいいから、とにかく筆を走らせてみろ。</p>',
                'required_clears' => 3,
            ],
            [
                'id' => 8,
                'name' => '改装中のお店のウワサ【月刊トリップ】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>月刊トリップです！今回は、閑話な内容を記事にしてみました。</p><p>広場にある、ずっと改装中の建物がありますよね...。<br>実はわたし、こそっと覗いちゃいました(怒られないでしょうか)。<br>すると、すごく綺麗な部屋にふかふかのベッドがたくさん！<br>う〜ん、宿屋でもできるんでしょうか？それにしては豪勢すぎる気もします。</p><p>看板も用意ができているように見えました。<br>もしかすると、ようやく<b>近日開店</b>するんじゃないでしょうか！</p><p>楽しみですね〜。どんな施設ができるんでしょう！ワクワクです！</p>',
                'required_clears' => 4,
            ],
            [
                'id' => 9,
                'name' => 'ショップのおすすめ品【月刊トリップ】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>月刊トリップだ。</p><p>近頃、とある冒険者たちが大陸の開拓をどんどん進めているらしい。<br>そのおかげで採取できる素材も増えて、ショップの品数も潤沢になってきたな。<br>今回は月刊トリップ直伝、これだけは買っとけって商品を紹介するぜ。</p><p>まずは「<b>Aポーション</b>」だ。<br>まあとにかく、やばい！って時にとりあえず使えるアイテムだな。</p><p>次は「<b>マナウォーター</b>」だ。<br>強敵との戦闘でAPが枯渇して引き返したことはないか？<br>これ1本持っておくだけでグッと機会が減るぜ。財布には優しくないけどな。</p><p>最後は、最近入荷された「<b>リザレクトポット</b>」だ。<br>戦闘不能の味方に使うと気力を取り戻す、絶対に持っといた方がいい一品だな。<br>あるだけで安定感爆上がりだ。</p><p>どれも便利すぎて、毎回買いすぎちまうぜ。<br>あーあー、また日雇いギルドでバイトしなきゃな。あそこキツいんだよな...。</p>',
                'required_clears' => 5,
            ],
            [
                'id' => 10,
                'name' => 'スキルは基礎倍率だけじゃない',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>俺は自分で言うのもなんだが、スキル研究家だ。</p><p>それぞれの職業スキルには「基礎倍率」が設定されているものが殆どだ。<br>ただし、単にこれだけでスキルの効果全てが決まるわけじゃない。</p><p>実例として、基礎魔導「プチボルト(100%)」、中位魔導「エイルカリバー(150%)」を挙げてみようか。基礎倍率ってのは使い手のステータスが文字通り基礎値となるわけだ。使い手のINTが10(相当へっぽこな魔導師だが)、受け手のDEFとINTが0だった場合、「プチボルト」は10ダメージ, 「エイルカリバー」は15ダメージとなる。<p>...ただし、実際はそうじゃない。<br><b>驚くことに、「エイルカリバー」の方が30ほど大きいダメージが出るんだ。</b></p><p>つまり上位スキルは、ある程度のダメージが担保されているわけだ。<br>熟練することで習得可能になるスキルは基礎倍率だけでは測れない価値を持つものが多い。こればっかりは使ってみなければ分からないな。</p><p>広場に<b style="color:blue">癒しの館</b>も出来たことだし、色々なスキルを覚えて試してみるのはどうだ？</p>',
                'required_clears' => 5,
            ],
            [
                'id' => 11,
                'name' => '調査ギルド報告書',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>調査ギルドです。</p><p>諸冒険者の皆さまの活躍により、大陸開拓は近年まれに見る速度で進展しております。これまで未踏であった地域の調査が着実に進み、各地の安全確保と資源開発に大きな成果を挙げています。</p><p>その過程において、一部の隊員が古き伝承に語られる城下町と思しき遺構を目撃いたしました。詳細は未だ不明ながら、<b>もしこの城下町の全容を解明できれば、伝説の古城の所在も明らかになる可能性があります。</b></p><p>ギルドとしても調査隊を編成し、情報収集を進めてまいりますが、冒険者諸氏の協力なくしては成し得ません。探索の折には、この地に関するあらゆる発見や記録をお寄せいただければ幸いです。</p><p>皆さまの健闘と無事の帰還を祈念いたします。</p>',
                'required_clears' => 6,
            ],
            [
                'id' => 12,
                'name' => '上位魔物【月刊トリップ】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>月刊トリップです！<br>若干違うけど、どことなくあの魔物に似ているような...?</p><p>そんな魔物を見かけた方もいらっしゃるのではないでしょうか？<br>おそらくそれは、同じ種の上位の魔物に該当するかと思います！<br>魔物は色々な特性を持っていますが、基本的にはその特徴は同じです。リザード種であれば物理攻撃への耐性が高かったり、植物系の魔物なら弱体系の技を得意としていたり...。まあ、中には例外もいますが。</p><p>覚えるのは大変ですが経験豊富な冒険者さんなら、<br>これまでの経験を活かして立ち向かうことができそうですね。</p><p>え？覚えてない？それなら今すぐ、「<b>魔物図譜</b>」で復習ですよ！</p>',
                'required_clears' => 7,
            ],
            [
                'id' => 13,
                'name' => '管轄外資料の混入について',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>図書館　蔵書管理係です。<br>平素は当館をご利用賜り、厚く御礼申し上げます。</p><p>このたび館内書架におきまして、当館の受入基準および管轄外に属する書籍・文書が混入していた事案を確認いたしました。該当資料は当館識別票を有しておらず、目録情報・装丁・所蔵印等に不整合が認められます。</p><p>ご来館の皆様におかれましては、当該資料を発見された際にはその場でお近くの司書まで速やかにお知らせください。</p><p>あわせて、蔵書の無断搬入・移動等の不審な行為または不審な人物をお見かけになった場合もお伝えください。安全で快適な閲覧環境維持のため、皆様のご理解とご協力を賜れますと幸甚に存じます。</p><p>引き続き当館をよろしくお願い申し上げます。</p>',
                'required_clear_field_id' => FieldData::CastleTown,
            ],
            [
                'id' => 14,
                'name' => '奇妙なカカシ',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>(とある冒険家の、体験記のようだ。)</p><p>古城の周りが開拓されたもんだから、危険を承知でぶらついてきたんだ。</p><p>城下町の近くの、すっかり荒れた耕作地にさ。変なカカシがつっ立って、野良の魔物にがじがじと齧られたりして痛めつけられてやんの。するとだぜ。<b>暫くした後めちゃくちゃにキレ出して、その魔物たちをとんでもない力でぶっ飛ばしちまったんだ。</b></p><p>その後他の魔物がひょっこり出てきて、そのカカシの近くをうろついてるんだ。その魔物たちは、なんともなかったな。</p><p>ありゃあほんとに、触らぬ神に祟りなしって言葉が似合う体験だったなあ。<br>あいつと対面する時は、<b>何もせずやり過ごす</b>のが一番いいだろうな。</p>',
                'required_clear_field_id' => FieldData::AncientCastleAltar,
            ],
            [
                'id' => 15,
                'name' => '冒険者の限界【月刊トリップ】',
                'book_category' => Library::CATEGORY_ADVENTURE,
                'content' => '<p>月刊トリップだ。</p><p>経験を積めばレベルが上がる...<br>冒険者全員が体験したことがあることだろう。</p><p>ただし、そんな成長にも限界があるのを知っているか？<br>どうやら<b>レベルが30に達すると、その職業を極めた存在</b>になるらしい。</p><p>お目にかかったことはないが、<br>そんな奴がいたらまさしく伝承の英雄みたいに敵無しなんだろうな。</p>',
                'required_clears' => 11,
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
                'name' => '調査報告書: '.FieldData::Grassland->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $grassland_content,
                'required_clears' => 0,
            ],
            [
                'id' => 202,
                'name' => '調査報告書: '.FieldData::Desert->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $desert_content,
                'required_clears' => 1,
            ],
            [
                'id' => 203,
                'name' => '調査報告書: '.FieldData::Volcano->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $volcano_content,
                'required_clears' => 1,
            ],
            [
                'id' => 204,
                'name' => '調査報告書: '.FieldData::Coast->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $coast_content,
                'required_clears' => 1,
            ],
            [
                'id' => 205,
                'name' => '(未完成)調査報告書: '.FieldData::WetFog->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $wetfog_preface.$caution,
                'required_clears' => 3,
            ],
            [
                'id' => 206,
                'name' => '調査報告書: '.FieldData::WetFog->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $wetfog_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::WetFog,
            ],
            [
                'id' => 207,
                'name' => '(未完成)調査報告書: '.FieldData::IceAndSnow->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $iceandsnow_preface.$caution,
                'required_clears' => 3,
            ],
            [
                'id' => 208,
                'name' => '調査報告書: '.FieldData::IceAndSnow->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $iceandsnow_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::IceAndSnow,
            ],
            [
                'id' => 209,
                'name' => '(未完成)調査報告書: '.FieldData::NightForest->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $nightforest_preface.$caution,
                'required_clears' => 3,
            ],
            [
                'id' => 210,
                'name' => '調査報告書: '.FieldData::NightForest->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $nightforest_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::NightForest,
            ],
            [
                'id' => 211,
                'name' => '(未完成)調査報告書: '.FieldData::DecayedFarmland->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $decayedfarmland_preface.$caution,
                'required_clears' => 6,
            ],
            [
                'id' => 212,
                'name' => '調査報告書: '.FieldData::DecayedFarmland->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $decayedfarmland_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::DecayedFarmland,
            ],
            [
                'id' => 213,
                'name' => '(未完成)調査報告書: '.FieldData::CastleTown->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $castletown_preface.$caution,
                'required_clears' => 6,
            ],
            [
                'id' => 214,
                'name' => '調査報告書: '.FieldData::CastleTown->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $castletown_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::CastleTown,
            ],
            [
                'id' => 215,
                'name' => '(未完成)調査報告書: '.FieldData::AncientCastle->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $ancientcastle_preface.$caution,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::CastleTown,
            ],
            [
                'id' => 216,
                'name' => '調査報告書: '.FieldData::AncientCastle->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $ancientcastle_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::AncientCastle,
            ],
            // 未完成無し
            [
                'id' => 217,
                'name' => '手記: '.FieldData::AncientCastleAltar->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $ancientcastlealtar_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::AncientCastleAltar,
            ],
            // 未完成無し
            [
                'id' => 218,
                'name' => '手記: '.FieldData::VastExpanse->label(),
                'book_category' => Library::CATEGORY_ENEMY,
                'content' => $vastexpanse_content,
                'required_clears' => null,
                'required_clear_field_id' => FieldData::VastExpanse,
            ],

            // 神話歴史学
            [
                'id' => 301,
                'name' => '大陸伝承I: 黎明',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>かつて、魔物が蔓延る大陸があった。<br>そこには噴煙を噴き上げる火山、凍てつく氷雪の荒野、陽光の届かぬ闇の領域が広がっていた。自然は苛烈にして、人の生を拒むかのようであった。<br>そんな大地に、ある日名もなき旅人たちが足を踏み入れた。</p><p>その瞬間こそ、この大陸の新たな伝承が幕を開けた時である。</p>',
                'required_clears' => 0,
            ],
            [
                'id' => 302,
                'name' => '大陸伝承II: 冒険',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>この大陸には、比類なき力を誇る魔物の集落が点在していた。<br>単独での踏破は不可能と悟った旅人は、三人一組の徒党を結成する。</p><p>剣を振るう者、魔術を操る者、仲間を支える者。<br>彼らは小さな拠点を築き、そこを起点に大陸の地を拓いていった。<br>草原を抜け、灼熱の火山を越え、やがて深き沼地をも踏み分ける。<br></p><p>そしてついに彼らの眼前に現れたのは、魔物の手で築かれた荘厳な石の城だった。</p>',
                'required_clears' => 1,
            ],
            [
                'id' => 303,
                'name' => '大陸伝承III: 繁栄',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>「ここが、大陸に蔓延る魔物の根城に違いない。」<br>旅人たちは城へ乗り込み、数多の魔物の群れへと立ち向かう。<br>激戦の果てに邪悪を退け、ついには城を支配する主をも討ち果たす。</p><p>その偉業は大陸中に響き渡り、人々は彼らを「英雄」として新たに崇め讃えた。</p><p>魔物の残党は退き、城の周囲には人が集い、富と秩序が築かれた。<br>かつて魔物の根城と恐れられた地周辺は、瞬く間に大陸随一の都へと変貌する。</p><p>こうして大陸は新たな時代を迎えたのであった。</p>',
                'required_clears' => 3,
            ],
            [
                'id' => 304,
                'name' => '大陸伝承IV: 衰退',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>繁栄の時代は幾十年にも及び、都は大陸の中心として栄華を極めた。</p><p>だがある日、英雄たちは誰に知られることもなく、忽然と姿を消す。<br>その理由を知る者はなく、人々はただ静かな不安を胸に抱いたという。<br></p><p>やがてその懸念は現実となり、忘れられたはずの魔物が再び大陸を侵した。<br>英雄無き世代では抗うことはできず、城は再び魔の手へと堕ちていった。<br>かくして繁栄の時代は幕を閉じ、大陸は再び魔物が蔓延ることとなったのである。<br>ただひとつ、今もなお語り継がれる言葉がある。</p><p style="color: gray; font-weight:bold">"かつて王国を築きし英雄の財宝は、未だ古城の奥深くに眠っているーー。"</p><p>この言葉は、人々を再び魔物に立ち向かわせるには十分な理由となった。<br>財宝を求める者、偉業を継がんとする者、失われた栄光を取り戻そうとする者。<br>英雄たちが築いた道は正しき秩序の礎とされ、多くの志ある者たちがその跡を追った。</p><p>大陸は再び人の世として歩み始めるため、今日へと至るのである。</p>',
                'required_clears' => 3,
            ],
            [
                'id' => 305,
                'name' => '書き殴られた本I',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>（...棚の隅に、本が挟み込まれている。図書館の管理印は見当たらない。）</p><hr><span style="color:red"><p>古き伝承は以下のように語っている。<br>「魔物は人を脅かし、旅人はそれを討ち英雄となった」と。<br>しかし、私はその記述に疑いを抱かずにはいられない。</p><p>果たして、魔物が人の暮らしをどれほど脅かしていたというのか。火を吹く龍が村を焼いた記録も、氷の魔物が人々を氷像とした記録も、どこにも残されてはいない。伝承の記録は「討伐」という結末のみを誇張し、その始まりを曖昧にしている。まるで誰かが都合のよい物語を編んだかのではないかとすら思わせる。</p><p>魔物は人を避けるため、山に籠り、沼に沈み、光の届かぬ地で静かに息づいていたのではないだろうか。彼らが真に人と敵対関係にあるならば、一体どうしてこの街は平穏を保っているのだろうか。</p><p>英雄と呼ばれる者たちが魔物に武器を振るった理由とは何か。<br>正義か、使命か。それともただ己の力を誇示したかっただけなのか。</p><p>この大陸の「成り立ち」とされる伝承は、本当に成り立ちなのだろうか。</p></span>',
                'required_clears' => null,
                'required_clear_field_id' => FieldData::CastleTown,
            ],
            [
                'id' => Library::VAST_EXPANSE_FLAG_BOOK_ID,
                'name' => '書き殴られた本II',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>（...棚の隅に、本が挟み込まれている。図書館の管理印は見当たらない。）</p><hr><span style="color: red"><p>今日まで続く歴史の果て、ついに伝承に語られる城を探索する冒険者が現れた。それはかつての英雄を彷彿とさせるかのような三人組らしい。その報せに街はかつてなく沸き立ち、ギルドも古き城の調査を進めている。だが、愚かにも彼らの関心は伝承に記された財宝のみ。周辺に残る古き痕跡にも、誰ひとり目を向けようとはしないのだろう。</p><p>某日、私はひとり、城から少し離れた耕作地を散策した。奇妙な魔物が多く無我夢中で逃げ回っている最中のことだ。<b>崩れ落ちた瓦礫の下に、地下へと続く階段</b>を見つけたのである。降りてゆくと、そこは牢獄の跡と思しき場所だった。壁や床には乾ききった血痕のようなものが点々と残り、長い年月の怨嗟がいまだそこに漂っているように思えた。その陰惨たる場所の中央に薄青く輝く不気味な光。異界へ通じるかのような、魔法で創られたポータルが存在していた。</p><p>その瞬間、私は確信した。これこそ疑わしき伝承の真実を解き明かす鍵であると。ギルドや街の者たちが夢中になっている財宝など、これに比べれば塵芥に等しい。後日私は準備を整え、このポータルの向こうへ足を踏み入れることを決めた。</p><p>私がこの手で、偽りなき大陸の記憶を綴ってみせよう。</p></span>',
                'required_clear_field_id' => FieldData::AncientCastle,
            ],
            [
                'id' => 307,
                'name' => '不気味な日記',
                'book_category' => Library::CATEGORY_HISTORY,
                'content' => '<p>（茫洋の地で拾った、日記帳だ。）</p><hr><span style="color: red"><p>【一日目】<br>ポータルを抜けた先は、果ての見えぬ虚無の大地だった。<br>地平の先まで何もなく、振り返っても入ってきた光の門は影も形も見当たらない。 どうやら進むほかに道はないようだ。<br>いま、誰も知らない歴史の頁をめくろうとしている。この震えは未知への恐怖心か、それとも武者震いだろうか。</p><p>【三日目】<br>違和感に気づいた。<br>寝食を忘れるほど調査に没頭していたことは認めるが、言葉通りこの世界に入ってから腹の虫は鳴かず、喉の渇きも覚えない。この空間の大気そのものが魔力が満ちており、生命活動に必要なエネルギーを自然に取り込むことができているようだ。理屈では理解できても、実際体験してみると全く異常である。</p><p>【五日目】<br>生命体を発見した。<br>それは人間とも魔物とも似つかない悍ましさを備えていた。<br>私の探究心が激しく疼いたと同時に、冒険者としての絶対的な直感が近づくなと叫ぶ。結局、遠目で観察することしかできなかった。</p><p>【七日目】<br>恐ろしいことと同時に、奇妙なことが起きた。<br>件の生命体と鉢合わせ、襲われた。あまりの恐怖と全ての終わりを覚悟したその刹那、私を救ったのは魔物であった。あの存在よりもか弱く見えたが、驚くほどの俊敏さで私を背に乗せ、空間を駆け抜けた。親切心を持ち合わせていたのか、空間の片隅に存在していた光るポータルまで私を運んでくれ、そしてけたたましい鳴き声を上げながら再び駆けていった。</p><p>ポータルには奇妙な呪文が施されていた。特定の存在を拒み、通さぬよう細工されている。察するに、この空間はあの生命体を封じ込めるための監獄なのだろう。</p><p>どこに通じているか分からないポータルだが、入ることであの生命体から逃避でき、現世への帰還も可能かもしれない。筆を取る今も襲われた事実に震えが止まらない。しかし、自分の探究心が許してはくれないことも同時に悟った。</p><p>あの生命体は何者なのか。人間に友好的な振る舞いを見せた魔物の理由は。</p><p>出口と思われる光を背に、私は探索を続けることを決意した。この空間に残る謎、すべてを解き明かして持ち帰ってみせよう。</p><p>【__日目】<br>ついに、例の生命体を間近に観察することに成功した。人の形に見える姿だが腕は四本にも及び、驚くべき身体能力と魔法すら自在に操る様は高い智力を想起させる。背面から漂う禍々しい気配は、絶望そのものを感じた。</p><p>だが、私が決定的な違和感を覚えたのはこれらではない。生命体が操っていた技の数々は、間違いなく<b>我々冒険者が魔物と対峙する際に使い、目にする能力</b>が含まれていた。<br>こ__...<br></p></span><hr><p>（黒ずんだ滲みとともに、文章はここで途切れている...。）</p>',
                'required_clear_field_id' => FieldData::VastExpanse,
            ],
            // [
            //     'id' => 397,
            //     'name' => '草原クリア後に出る本',
            //     'book_category' => Library::CATEGORY_HISTORY,
            //     'content' => '<p>草原</p>',
            //     'required_clear_field_id' => FieldData::Grassland,
            // ],
            // [
            //     'id' => 398,
            //     'name' => '砂クリア後に出る本',
            //     'book_category' => Library::CATEGORY_HISTORY,
            //     'content' => '<p>砂漠</p>',
            //     'required_clear_field_id' => FieldData::Desert,
            // ],
            // [
            //     'id' => 399,
            //     'name' => '氷雪地帯クリア後に出る本',
            //     'book_category' => Library::CATEGORY_HISTORY,
            //     'content' => '<p>氷雪</p>',
            //     'required_clear_field_id' => FieldData::IceAndSnow,
            // ],
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
