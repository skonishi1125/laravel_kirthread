<?php

namespace App\Http\Controllers\Game\Rpg;

use App\Http\Controllers\Controller;
use App\Models\Game\Rpg\Enemy;
use App\Models\Game\Rpg\Field;
use App\Models\Game\Rpg\Item;
use App\Models\Game\Rpg\Party;
use App\Models\Game\Rpg\SaveData;
use App\Models\Game\Rpg\Skill;
Use Auth;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Http\Request;

class ApiController extends Controller
{

  // TODO: 
  // POSTのページに直接アクセスしたときエラーログに残るのでリダイレクトされるようにしたい

  // ショップ
  public function shopList() {
    $shop_list_items = Item::getShopListItem();
    return $shop_list_items;
  }
  public function paymentItem(Request $request) {
    $money = $request->money;
    $item_price = $request->price;
    $number = (int)$request->number;

    // 決済処理
    // エラー時はthrow new Exceptionだとうまくvue側でcatchできないので、responseで返して受け取る。
    try {
      // 数量が0, マイナスで指定された場合はその旨を返す。
      if ($number < 1) {
        return response()->json(['error' => '数量を1以上指定してください'], 400);
      }
      $total_price = $item_price * $number;
      $after_payment_money = $money - $total_price;

      if ($after_payment_money < 0) {
        return response()->json(['error' => '所持金額が足りません。'], 400);
      }

      $savedata = SaveData::getLoginUserCurrentSaveData();

      // TODO: アイテムが増える挙動も書く。その際はトランザクションを使う。
      $savedata->update([
        'money' => $after_payment_money
      ]);

      Debugbar::info($money, $item_price, $number, $total_price, $after_payment_money, $savedata);


    } catch (Exception $e) {

    }
  }

  // ログインユーザーの現在のステータス
  public function loginUserCurrentSaveData() {
    $current_save_data = SaveData::getLoginUserCurrentSaveData();
    return $current_save_data;
  }

  // フィールド
  public function fieldList() {
    return Field::get();
  }

  // 戦闘
  // 戦闘開始
  public function setEncountElement(Request $request) {
    $fieid_id = 1; // todo:$requestから取得する
    $user_id = Auth::id();

    $parties = Party::where('user_id', $user_id)->get();

    // パーティ3人の名前, HP/MP, スキルを格納する
    $players_data = collect();
    foreach ($parties as $party) {
      $learned_skill_ids = $party->skills()->pluck('rpg_skills.id');
      $learned_skill = Skill::select('name', 'description')
        ->whereIn('id', $learned_skill_ids)
        ->get();

      $player_data = collect([
        'nickname' => $party->nickname,
        'value_hp' => $party->value_hp,
        'value_ap' => $party->value_ap,
        'skills' => $learned_skill
        // todo: 画像も。
      ]);
      $players_data->push($player_data);
    }

    // 敵の名前、HP/MPを格納する
    // todo: 出現させる敵は何かとか考えとく。
    $enemies_data = collect();
    $enemies = Enemy::where('appear_field_id', 1)->get();
    foreach ($enemies as $enemy) {
      $enemy_data = collect([
        'name' => $enemy->name,
        'image' => $enemy->portrait_image_path,
      ]);
      $enemies_data->push($enemy_data);
    }

    $all_data = collect();
    $all_data->push($players_data);
    $all_data->push($enemies_data);

    return $all_data;
  }

  // 選択されたデータを元に、コマンド実行
  public function execBattleCommand(Request $request) {

    $commands = $request->all();
    Debugbar::info($commands);



  }



}
