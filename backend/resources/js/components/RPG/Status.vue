<style scoped>
.sub-sucreen-text-space {
    padding: 10px 0px;
}

.character-nav-tab {
  cursor: pointer;
}
.btn-status-modal {
  border: 1px dotted black;
}
.badge-light-status {
  color: white;
  background-color: #dc3545 !important;
}

.badge-light-physical {
  color: white;
  background-color: #cf5555 !important;
}
.badge-light-magic {
  color: white;
  background-color: #5573cf !important;
}
.badge-light-buff {
  color: white;
  background-color: #ffc107 !important;
}
.badge-light-self {
  color: white;
  background-color: #ffc107 !important;
}
.badge-light-single {
  color: white;
  background-color: #fd7e14 !important;
}
.badge-light-all {
  color: white;
  background-color: #dc3545 !important;
}
.skill-items {
  margin: 20px;
}
.freely-point-red {
  color: red;
}

</style>

<template>
  <div class="container">
    <div v-if="status.status == 'start'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>読み込み中...</small></p>
            <hr>
          </div>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12"></div>
      </div>
    </div>

    <div v-if="status.status == 'status'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p>
              <small>メンバーのステータス及びスキルの確認・ポイントの振り分けができます。</small>
            </p>
          </div>
          <hr>
          <div>
            <!-- <p>aaa</p> -->
          </div>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12">
          <ul class="nav nav-tabs">
            <!-- クリックした時そのキャラの情報を取得するようにして、activeクラスを張り替えるような実装にするとよい -->
            <a class="nav-link character-nav-tab"
              :class="{'active': status.currentSelectedPartyMemberIndex === 0}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 0)"
            >
              {{ partiesInformation[0].nickname }}
            </a>
            <a class="nav-link character-nav-tab" 
              :class="{'active': status.currentSelectedPartyMemberIndex === 1}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 1)"
            >
              {{ partiesInformation[1].nickname }}
            </a>
            <a class="nav-link character-nav-tab" 
              :class="{'active': status.currentSelectedPartyMemberIndex === 2}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 2)"
            >
              {{ partiesInformation[2].nickname }}
            </a>
          </ul>
  
          <div class="row">
            <div class="col-2 my-5">
              <div style="min-height: 300px;  display: flex; flex-flow: column;  justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
                <div>
                  <button class="btn btn-sm btn-outline-info"
                    :class="{'active': status.status == 'status'}"
                    @click="toggleStatusStatus('status')"
                    >
                      ステータス
                    </button>
                  </div>
                <div>
                  <button class="btn btn-sm btn-outline-info"
                    :class="{'active': status.status == 'skill'}"
                    @click="toggleStatusStatus('skill')"
                  >
                    スキル確認
                  </button>
                </div>
              </div>
            </div>

            <div class="col-10 my-5" style="max-height: 300px; overflow-y: scroll;">
              <div class="col-12" style="border-bottom: 1px solid black;">
                <h6>
                  {{ partiesInformation[status.currentSelectedPartyMemberIndex].nickname }}
                  <small>
                    【{{ partiesInformation[status.currentSelectedPartyMemberIndex].role_class }}:{{ partiesInformation[status.currentSelectedPartyMemberIndex].role_class_japanese }}】
                    <span class="badge badge-primary">Lv.{{ partiesInformation[status.currentSelectedPartyMemberIndex].level }}</span>
                    / Next: <small><b>{{ partiesInformation[status.currentSelectedPartyMemberIndex].next_level_up_exp }}</b></small> Exp
                    / Total: <small><b>{{ partiesInformation[status.currentSelectedPartyMemberIndex].total_exp }}</b></small> Exp
                  </small>
                </h6>
              </div>

              <!-- ステータス一覧 -->
              <div class="container-fluid my-1">
                <div class="row my-4">
                  <div class="col-4">
                    <div>
                      <div class="d-flex justify-content-between">
                        <span>HP: {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_hp }}</span>
                        <span><button class="btn btn-sm btn-status-modal" @click="displayStatusConfirmModal('HP', partiesInformation[status.currentSelectedPartyMemberIndex].status.value_hp)">+</button></span>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span>AP: {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_ap }}</span>
                        <span><button class="btn btn-sm btn-status-modal" @click="displayStatusConfirmModal('AP', partiesInformation[status.currentSelectedPartyMemberIndex].status.value_ap)">+</button></span>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span>STR: {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_str }}</span>
                        <span><button class="btn btn-sm btn-status-modal" @click="displayStatusConfirmModal('STR', partiesInformation[status.currentSelectedPartyMemberIndex].status.value_str)">+</button></span>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span>DEF: {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_def }}</span>
                        <span><button class="btn btn-sm btn-status-modal" @click="displayStatusConfirmModal('DEF', partiesInformation[status.currentSelectedPartyMemberIndex].status.value_def)">+</button></span>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span>INT: {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_int }}</span>
                        <span><button class="btn btn-sm btn-status-modal" @click="displayStatusConfirmModal('INT', partiesInformation[status.currentSelectedPartyMemberIndex].status.value_int)">+</button></span>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span>SPD: {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_spd }}</span>
                        <span><button class="btn btn-sm btn-status-modal" @click="displayStatusConfirmModal('SPD', partiesInformation[status.currentSelectedPartyMemberIndex].status.value_spd)">+</button></span>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span>LUC: {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_luc }}</span>
                        <span><button class="btn btn-sm btn-status-modal" @click="displayStatusConfirmModal('LUC', partiesInformation[status.currentSelectedPartyMemberIndex].status.value_luc)">+</button></span>
                      </div>
                    </div>
                  </div>
                  <!-- マウスカーソルで説明欄に出した方が丸いかな。 -->
                  <div class="col-8">
                    <p style="font-size: 14px">
                      <b>※ステータスについて</b><br>
                      <small><b>HP</b></small>: 生命力であり、0になると戦闘不能となります。<br>
                      <small><b>AP</b></small>: 使用することで強力なスキルが使用できます。<br>
                      <small><b>STR</b></small>: 物理攻撃力に影響します。<br>
                      <small><b>DEF</b></small>: 物理防御力及び、魔法防御力に影響します。<br>
                      <small><b>INT</b></small>: 魔法攻撃力及び、魔法防御力に影響します。<br>
                      <small><b>SPD</b></small>: 行動速度及び、戦闘からの逃走率に影響します。<br>
                      <small><b>LUC</b></small>: いいことが沢山起こりやすくなります。<br>
                    </p>
                  </div>
                </div>
              </div>


            </div> <!-- class="col-10 my-5" -->


          </div>

          <div class="row">
            <div class="col-12">
              <p style="font-size: 0.85rem; font-weight: bold;">
                未振り分けのステータスポイント:【
                  <span :class="{ 'freely-point-red': partiesInformation[status.currentSelectedPartyMemberIndex].freely_status_point > 0 }">
                    {{ partiesInformation[status.currentSelectedPartyMemberIndex].freely_status_point }}
                  </span>
                】
                スキルポイント:【
                  <span :class="{ 'freely-point-red': partiesInformation[status.currentSelectedPartyMemberIndex].freely_skill_point > 0}">
                    {{ partiesInformation[status.currentSelectedPartyMemberIndex].freely_skill_point }}
                  </span>
                】
                ※スキルツリーはスクロール可能。
                <br>
                <p v-if="successStatusMessage !== null" style="color:red; margin-top: 8px">
                  {{ successStatusMessage }}
                </p>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- ステータスモーダル -->
      <teleport to="body">
        <div class="modal fade" id="modal-status-confirm" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h6 class="modal-title"><b>ステータス 【{{ modalStatusName }}】</b></h6>
                <button type="button" class="close" data-dismiss="modal" aria-rabel="Close"><span aria-hidden="true">&times;</span></button>
              </div>

              <div class="modal-body">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <p>
                        何ポイント振り分けますか？<br>
                        <small>※未振り分けのステータスポイント: {{ partiesInformation[status.currentSelectedPartyMemberIndex].freely_status_point - inputFreelyStatusPoints }}</small>
                      </p>
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroup-sizing-sm">振り分けるポイント数: </span>
                        </div>
                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                          v-model.number="inputFreelyStatusPoints"
                          @input="validateStatusInput"
                          :max="partiesInformation[status.currentSelectedPartyMemberIndex].freely_status_point"
                          min="0"
                          placeholder="振り分けるポイントを入力"
                        >
                      </div>
                    </div>

                    <div class="col-3"></div>
                    <div class="col-6">
                      <div class="d-flex justify-content-between">
                        <span class="badge badge-secondary" style="padding: 6px 10px">{{ modalStatusName }}:{{ modalStatusBaseValue }}</span>
                        <span>→</span>
                        <span v-if="modalStatusName !== 'HP'">
                          <span class="badge badge-primary" style="padding: 6px 10px">{{ modalStatusName }}:{{ modalStatusBaseValue + inputFreelyStatusPoints }}</span>
                        </span>
                        <!-- HPは倍伸ばす -->
                        <span v-else>
                          <span class="badge badge-primary" style="padding: 6px 10px">{{ modalStatusName }}:{{ modalStatusBaseValue + (inputFreelyStatusPoints * 2) }}</span>
                        </span>
                      </div>
                    </div>
                    <div class="col-3"></div>

                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <p style="font-size: 14px">
                        <b>※HPステータスについて</b><br>
                        HPのみ振り分けた際の伸びが大きく、1ポイントで2UPします。
                      </p>
                    </div>
                  </div>
                </div>

                <div v-if="modalErrorMessage != null">
                  <p style="font-size: 13px; color:red">{{ modalErrorMessage }}</p>
                </div>
                <!-- ポイントがない場合は押せなくするとよい -->
                <button type="button" class="btn btn-info" @click="postIncrementStatus"
                  :disabled="inputFreelyStatusPoints < 1 || partiesInformation[status.currentSelectedPartyMemberIndex].freely_status_point < 1"
                >
                  確定
                </button>

              </div>

            </div>
          </div>
        </div> <!-- modal -->
      </teleport>

    </div>

    <!-- axiosで受け取れてから表示させる -->
    <div v-if="status.status == 'skill' && Object.keys(partiesInformation).length > 0">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p>
              <small>メンバーのステータス及びスキルの確認・ポイントの振り分けができます。</small>
            </p>
          </div>
          <hr>
          <div v-if="Object.keys(skillInformation).length > 0">
            <div style="font-size: 0.9rem;">
              <p>
                <b>{{ skillInformation.skill_name }}</b>
                <span v-if="skillInformation.skill_level == 0">【<small><b>未習得</b></small>】</span>
                <span v-else>【習得済<small>(SLv:<b>{{ skillInformation.skill_level }}</b>)</small>】</span>
                <span class="badge badge-light"
                  :class="{
                    'badge-light-physical': skillInformation.attack_type === '物理',
                    'badge-light-magic': skillInformation.attack_type === '魔法',
                    }"
                >
                  {{ skillInformation.attack_type }}
                </span>
                <span class="badge"
                  :class="{
                    'badge-primary': skillInformation.effect_type === '攻撃',
                    'badge-success': skillInformation.effect_type === '回復',
                    'badge-light badge-light-buff': skillInformation.effect_type === 'バフ',
                    }"
                >
                  {{ skillInformation.effect_type }}
                </span>
                <span class="badge badge-light"
                  :class="{
                    'badge-light-self': skillInformation.target_range === '自身',
                    'badge-light-single': skillInformation.target_range === '単体',
                    'badge-light-all': skillInformation.target_range === '全体',
                    }"
                >
                  {{ skillInformation.target_range }}
                </span>
                <br>
                {{ skillInformation.description }} <small style="color:red">{{ skillInformation.conditions }}</small>
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12">
          <ul class="nav nav-tabs">
            <!-- クリックした時そのキャラの情報を取得するようにして、activeクラスを張り替えるような実装にするとよい -->
            <a class="nav-link character-nav-tab"
              :class="{'active': status.currentSelectedPartyMemberIndex === 0}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 0)"
            >
              {{ partiesInformation[0].nickname }}
            </a>
            <a class="nav-link character-nav-tab" 
              :class="{'active': status.currentSelectedPartyMemberIndex === 1}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 1)"
            >
              {{ partiesInformation[1].nickname }}
            </a>
            <a class="nav-link character-nav-tab" 
              :class="{'active': status.currentSelectedPartyMemberIndex === 2}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 2)"
            >
              {{ partiesInformation[2].nickname }}
            </a>
          </ul>
  
          <div class="row">
            <div class="col-2 my-5">
              <div style="min-height: 300px;  display: flex; flex-flow: column;  justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
                <div>
                  <button class="btn btn-sm btn-outline-info"
                    :class="{'active': status.status == 'status'}"
                    @click="toggleStatusStatus('status')"
                    >
                      ステータス
                    </button>
                  </div>
                <div>
                  <button class="btn btn-sm btn-outline-info"
                    :class="{'active': status.status == 'skill'}"
                    @click="toggleStatusStatus('skill')"
                  >
                    スキル確認
                  </button>
                </div>
              </div>
            </div>

            <div class="col-10 my-5" style="max-height: 300px; overflow-y: scroll;">
              <!-- 0:灰/特殊 1:青/攻撃 2:緑/回復 3:黄/バフ -->
              <ul v-for="parentSkill in skillTreeArray[status.currentSelectedPartyMemberIndex]">
                <li class="skill-items">
                  <button class="btn btn-sm"
                    :class="{
                      'btn-outline-secondary': parentSkill.effect_type === 0,
                      'btn-outline-primary': parentSkill.effect_type === 1,
                      'btn-outline-success': parentSkill.effect_type === 2,
                      'btn-outline-warning': parentSkill.effect_type === 3,
                    }"
                    @click="displaySkillConfirmModal(parentSkill)"
                    @mouseover="showSkillInformation(parentSkill)"
                    :disabled="!parentSkill.is_learned"
                  >
                    <span v-if="parentSkill.is_learned">{{ parentSkill.skill_name }} <small>(SLv:<b>{{ parentSkill.skill_level }}</b>)</small></span>
                    <span v-else="parentSkill.is_learned">???</span>
                  </button>
                </li>
                <div v-if="parentSkill.child_skills">
                  <ul v-for="childSkill in parentSkill.child_skills">
                    <li>
                      <button class="btn btn-sm"
                        :class="{
                          'btn-outline-secondary': parentSkill.effect_type === 0,
                          'btn-outline-primary': parentSkill.effect_type === 1,
                          'btn-outline-success': parentSkill.effect_type === 2,
                          'btn-outline-warning': parentSkill.effect_type === 3,
                        }"
                        @click="displaySkillConfirmModal(childSkill)"
                        @mouseover="showSkillInformation(childSkill)"
                        :disabled="!childSkill.is_learned"

                      >
                        <span v-if="childSkill.is_learned">{{ childSkill.skill_name }}<small>(SLv:<b>{{ childSkill.skill_level }}</b>)</small></span>
                        <span v-else="childSkill.is_learned">???</span>
                      </button>
                    </li>
                  </ul>
                </div>
              </ul>
            </div> <!-- class="col-10 my-5" -->
          </div>

          <div class="row">
            <div class="col-12">
              <p style="font-size: 0.85rem; font-weight: bold;">
                未振り分けのステータスポイント:【
                  <span :class="{ 'freely-point-red': partiesInformation[status.currentSelectedPartyMemberIndex].freely_status_point > 0 }">
                    {{ partiesInformation[status.currentSelectedPartyMemberIndex].freely_status_point }}
                  </span>
                】
                スキルポイント:【
                  <span :class="{ 'freely-point-red': partiesInformation[status.currentSelectedPartyMemberIndex].freely_skill_point > 0}">
                    {{ partiesInformation[status.currentSelectedPartyMemberIndex].freely_skill_point }}
                  </span>
                】
                ※スキルツリーはスクロール可能。
                <br>
                <p v-if="successSkillMessage !== null" style="color:red; margin-top: 8px">
                  {{ successSkillMessage }}
                </p>
              </p>

            </div>
          </div>
        </div>
      </div>

      <!-- モーダル内部でもaxiosで受け取った情報を使用するため、読み込み後のdiv要素に含める -->
      <teleport to="body">
        <div class="modal fade" id="modal-skill-confirm" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h6 class="modal-title">
                  <b>{{ modalSkillInfo.skill_name }}</b>
                  <span class="badge badge-light"
                      :class="{
                        'badge-light-physical': modalSkillInfo.attack_type === '物理',
                        'badge-light-magic': modalSkillInfo.attack_type === '魔法',
                        }"
                  >
                    {{ modalSkillInfo.attack_type }}
                  </span>
                  <span class="badge"
                    :class="{
                      'badge-primary': modalSkillInfo.effect_type === '攻撃',
                      'badge-success': modalSkillInfo.effect_type === '回復',
                      'badge-light badge-light-buff': modalSkillInfo.effect_type === 'バフ',
                      }"
                  >
                    {{ modalSkillInfo.effect_type }}
                  </span>
                  <span class="badge badge-light"
                    :class="{
                      'badge-light-self': modalSkillInfo.target_range === '自身',
                      'badge-light-single': modalSkillInfo.target_range === '単体',
                      'badge-light-all': modalSkillInfo.target_range === '全体',
                      }"
                  >
                    {{ modalSkillInfo.target_range }}
                  </span>
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-rabel="Close"><span aria-hidden="true">&times;</span></button>
              </div>
  
              <div class="modal-body">
                <div class="container-fluid">
                  <div class="row">
                    <!-- スキルレベルがnull (未習得)の時 -->
                    <div v-if="!modalSkillInfo.current_skill_level">
                      <div class="col-12">
                        <span class="badge badge-info">NEXT</span>
                        <p>
                          【スキルレベル: {{ modalSkillInfo.next_skill_level}}】<br>
                          使用AP: <span style="color:orange">{{ modalSkillInfo.next_skill_ap_cost }}</span><br>
                          基礎倍率: <span style="color:orange">{{ modalSkillInfo.next_skill_percent }}</span>% <br>
                          <span v-if="modalSkillInfo.next_skill_buff_turn"> ターン数: <span style="color:orange">{{ modalSkillInfo.next_skill_buff_turn }}</span></span>
                        </p>
                      </div>
                    </div>
                    <!-- スキルレベルが1 or 2の時 -->
                    <div v-else-if="modalSkillInfo.current_skill_level < 3">
                      <div class="col-12">
                        <span class="badge badge-secondary">現在</span>
                        <p>
                          【スキルレベル: {{ modalSkillInfo.current_skill_level }}】<br>
                          使用AP: <span style="color:orange">{{ modalSkillInfo.current_skill_ap_cost }}</span> <br>
                          基礎倍率: <span style="color:orange">{{ modalSkillInfo.current_skill_percent }}</span>% <br>
                          <span v-if="modalSkillInfo.current_skill_buff_turn"> ターン数: <span style="color:orange">{{ modalSkillInfo.current_skill_buff_turn }}</span></span>
                        </p>
                        <hr>
                        <div style="text-align: center;">
                          <p>↓</p>
                        </div>
                        <span class="badge badge-info">NEXT</span>
                        <p>
                          【スキルレベル: {{ modalSkillInfo.next_skill_level}}】<br>
                          使用AP: <span style="color:orange">{{ modalSkillInfo.next_skill_ap_cost }}</span> <br>
                          基礎倍率: <span style="color:orange">{{ modalSkillInfo.next_skill_percent }}</span>% <br>
                          <span v-if="modalSkillInfo.next_skill_buff_turn"> ターン数: <span style="color:orange">{{ modalSkillInfo.next_skill_buff_turn }}</span></span>
                        </p>
                      </div>
                    </div>
  
                    <!-- スキルレベルが3 (max)の時 -->
                    <div v-else-if="modalSkillInfo.current_skill_level > 2">
                      <div class="col-12">
                        <span class="badge badge-dark">MAX</span>
                        <p>
                          【スキルレベル: {{ modalSkillInfo.current_skill_level }}】<br>
                          使用AP: <span style="color:orange">{{ modalSkillInfo.current_skill_ap_cost }}</span><br>
                          基礎倍率: <span style="color:orange">{{ modalSkillInfo.current_skill_percent }}</span>% <br>
                          <span v-if="modalSkillInfo.current_skill_buff_turn"> ターン数: <span style="color:orange">{{ modalSkillInfo.current_skill_buff_turn }}</span></span>
                        </p>
                      </div>
                    </div>
  
                  </div>
                </div>
              </div>
  
              <div class="modal-footer">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <p style="font-size: 14px">
                        <b>※基礎倍率</b><br>
                        ステータスの値を100%とした、スキル使用時の効果倍率。<br>
                        【例】<br>
                        ●<span class="badge badge-light badge-light-physical">物理</span><span class="badge badge-primary">攻撃</span>, 基礎倍率200%のスキル<br>
                        　→通常攻撃(基礎倍率100%)の倍程度のダメージ。<br>
                        ●<span class="badge badge-light badge-light-magic">魔法</span><span class="badge badge-success">回復</span>, 基礎倍率50%のスキル<br>
                        　→基礎倍率100%の魔法スキルのダメージの半分程度の回復量。
                      </p>
                    </div>
                  </div>
                  <div v-if="modalErrorMessage != null">
                    <p style="font-size: 13px; color:red">{{ modalErrorMessage }}</p>
                  </div>
                </div>
                <div v-if="modalSkillInfo.current_skill_level !== 3">
                  <button type="button" class="btn btn-info" @click="postLearnSkillData(modalSkillInfo)"
                    :disabled="partiesInformation[status.currentSelectedPartyMemberIndex].freely_skill_point < 1"
                  >
                    習得
                  </button>
                </div>
                <div v-else-if="modalSkillInfo.current_skill_level === 3">
                  <small>このスキルはマスターしています。</small>
                </div>
              </div>
  
            </div>
          </div>
        </div>
      </teleport> 

    </div>
  </div>


</template>

<script>
  import $ from 'jquery';
  import { mapState } from 'vuex';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {
        partiesInformation: [],
        statusArray: [],
        skillTreeArray: [],
        hoveredDescription: null, // 現在マウスオーバーしている要素の説明
        errorMessage: null,
        successSkillMessage: null,
        successStatusMessage: null,
        modalErrorMessage: null,
        skillInformation: {},
        modalStatusName: '',
        modalStatusBaseValue: null,
        inputFreelyStatusPoints: 0,
        modalSkillInfo: {},
      }
    },
    computed: {
      ...mapState({
        status: state => state.menu.status,
      }),
    },
    created() {
      this.$store.dispatch('setMenuStatusStatus', 'start');
      this.getPartiesInformation();
    },
    mounted() {
      console.log(`Status.vue ${this.status.status}`);
    },
    methods: {
      getPartiesInformation() { 
        console.log(`getPartiesInformation(): -----------------`);
        axios.get('/api/game/rpg/parties/information')
          .then(response => {
            console.log(`response.data: ${response.data}`);
            this.partiesInformation = response.data;
            // console.log(this.partiesInformation[0],this.partiesInformation[0]['status'], this.partiesInformation[1]);

            console.log('スキルツリー変数格納開始。');
            this.partiesInformation.forEach(partyInformation => {
              this.statusArray.push(partyInformation['status']);
              this.skillTreeArray.push(partyInformation['skill_tree']);
            });

            // console.log(this.skillTreeArray[0], this.statusArray[0]);
            // 1人目のメンバーのスキル一覧の、1つ目のスキルの情報を参照したい場合
            // console.log(this.skillTreeArray[0][0]['skill_name'], this.skillTreeArray[0][0].child_skills);

            this.$store.dispatch('setMenuStatusStatus', 'status');
          }
        );
      },
      // 'status' or 'skill'画面に変える
      toggleStatusStatus(state) {
        this.$store.dispatch('setMenuStatusStatus', state);
      },
      displayStatusConfirmModal(status_name, status_value) {
        console.log(`displayStatusConfirmModal: ${status_name} ${status_value} -------`);
        this.successStatusMessage = null;
        this.modalStatusName = status_name;
        this.modalStatusBaseValue = status_value;
        this.inputFreelyStatusPoints = 0;
        $('#modal-status-confirm').modal('show');
      },
      validateStatusInput(event) {
        const value = Number(event.target.value);
        const maxPoints = this.partiesInformation[this.status.currentSelectedPartyMemberIndex].freely_status_point;

        if (maxPoints === 0) {
          this.inputFreelyStatusPoints = maxPoints; // 最大値を超えた場合は最大値に
          this.modalErrorMessage = `振り分け可能なステータスポイントがありません。`;
        } else if (value > maxPoints) {
          this.inputFreelyStatusPoints = maxPoints; // 最大値を超えた場合は最大値に
          this.modalErrorMessage = `${maxPoints}ポイント以上を指定することはできません。`;
        } else if (value < 0) {
          this.inputFreelyStatusPoints = 0; // 最小値を下回った場合は0に
          this.modalErrorMessage = 'マイナスの値を指定することはできません。';
        }
      },
      postIncrementStatus() {
        console.log(`${this.inputFreelyStatusPoints}, ${this.modalStatusName}, ${this.modalStatusBaseValue}`);

        // 未振り分けのステータスポイントが1以上ある場合のみ、処理を行う
        if (this.partiesInformation[this.status.currentSelectedPartyMemberIndex].freely_status_point > 0) {
          // axios.postで登録
          axios.post(`/api/game/rpg/status/increment`,{
            party_id: this.partiesInformation[this.status.currentSelectedPartyMemberIndex].party_id,
            input_point: this.inputFreelyStatusPoints,
            status_type: this.modalStatusName,
          })
          .then(response => {
            console.log(`通信OK`);
            console.log(response.data.message);

            // 振り分け後のデータ再取得
            this.updatePartiesInformation();

            // stateを'status'に戻し、modalを閉じる
            this.$store.dispatch('setMenuStatusStatus', 'status');
            $('#modal-status-confirm').modal('hide');
            this.successStatusMessage = 'ステータスの振り分けが完了しました！';
          })
          .catch(error => {
            console.log(`通信失敗。`);
            if (error.response && error.response.data) {
              this.modalErrorMessage = error.response.data.message;
            } else {
              this.modalErrorMessage = "予期しないエラーが発生しました。画面リロードをお試しください。"
            }
            console.log(this.modalErrorMessage);
          });
        } else {
          console.log('ステータスポイントなし');
          this.modalErrorMessage = 'ステータスポイントが不足しています。';
        }

      },
      showSkillInformation(skill) {
        // console.log(`showSkillInformation: ${skill.skill_name} -------`);
        this.successSkillMessage = null;
        this.skillInformation = {
          skill_name: '???',
          skill_level: skill.skill_level,
          description: '-',
          attack_type: '-',
          effect_type: '-',
          target_range: '-',
        };

        // 習得可能なスキルの場合、各要素を開示させる
        if (skill.is_learned) {
          this.skillInformation.skill_name = skill.skill_name;
          this.skillInformation.description = skill.description;
          switch (skill.attack_type) {
            case 0:
              this.skillInformation.attack_type = "-"
              break;
            case 1:
              this.skillInformation.attack_type = "物理"
              break;
            case 2:
              this.skillInformation.attack_type = "魔法"
              break;
          };
          switch (skill.effect_type) {
            case 0:
              this.skillInformation.effect_type = "特殊"
              break;
            case 1:
              this.skillInformation.effect_type = "攻撃"
              break;
            case 2:
              this.skillInformation.effect_type = "回復"
              break;
            case 3:
              this.skillInformation.effect_type = "バフ"
              break;
            case 9:
              this.skillInformation.effect_type = "その他"
              break;
          };
  
          switch (skill.target_range) {
            case 0:
              this.skillInformation.target_range = "自身"
              break;
            case 1:
              this.skillInformation.target_range = "単体"
              break;
            case 2:
              this.skillInformation.target_range = "全体"
              break;
          };
        }

        // 習得条件メッセージの作成
        // 複数条件
        if (skill.requirement_skill_level && skill.requirement_party_level) {
          // console.log('a');
          this.skillInformation.conditions = `【条件】Lv${skill.requirement_party_level}以上,${skill.parent_skill_name} SLv${skill.requirement_skill_level}以上`;
        // SLv条件
        } else if (skill.requirement_skill_level && skill.requirement_party_level == null) {
          // console.log('b');
          this.skillInformation.conditions = `【条件】${skill.parent_skill_name} SLv${skill.requirement_skill_level}以上`;
        // パーティLv条件
        } else if (skill.requirement_skill_level == null && skill.requirement_party_level) {
          // console.log('c');
          this.skillInformation.conditions = `【条件】Lv${skill.requirement_party_level}以上`;
        // 条件なし
        } else if (skill.requirement_skill_level == null && skill.requirement_party_level == null) {
          // console.log('d');
          this.skillInformation.conditions = '';
        }
      },
      clearSkillInformation() {
        console.log(`clearSkillInformation: -------`);
        this.skillInformation = {};
      },
      displaySkillConfirmModal(skill) {
        console.log(`displaySkillConfirmModal(): -----------------`);
        this.modalErrorMessage = null;
        // 取得条件を満たすスキルのみクリックできるようにする
        if (skill.is_learned) {
          this.modalSkillInfo = {
            skill_id: skill.skill_id,
            skill_name: skill.skill_name,
            current_skill_level: skill.skill_level,
            current_skill_percent: skill[`lv${skill.skill_level}_percent`] * 100 ?? null,
            current_skill_ap_cost: skill[`lv${skill.skill_level}_ap_cost`] ?? null,
            current_skill_buff_turn: skill[`lv${skill.skill_level}_buff_turn`] ?? null,
            next_skill_level: skill.skill_level + 1,
            next_skill_percent: skill[`lv${skill.skill_level + 1}_percent`] * 100 ?? null,
            next_skill_ap_cost: skill[`lv${skill.skill_level + 1}_ap_cost`] ?? null,
            next_skill_buff_turn: skill[`lv${skill.skill_level + 1}_buff_turn`] ?? null,
          };

          switch (skill.attack_type) {
            case 0:
              this.modalSkillInfo.attack_type = "-"
              break;
            case 1:
              this.modalSkillInfo.attack_type = "物理"
              break;
            case 2:
              this.modalSkillInfo.attack_type = "魔法"
              break;
          };

          switch (skill.effect_type) {
            case 0:
              this.modalSkillInfo.effect_type = "特殊"
              break;
            case 1:
              this.modalSkillInfo.effect_type = "攻撃"
              break;
            case 2:
              this.modalSkillInfo.effect_type = "回復"
              break;
            case 3:
              this.modalSkillInfo.effect_type = "バフ"
              break;
            case 9:
              this.modalSkillInfo.effect_type = "その他"
              break;
          };

          switch (skill.target_range) {
            case 0:
              this.modalSkillInfo.target_range = "自身"
              break;
            case 1:
              this.modalSkillInfo.target_range = "単体"
              break;
            case 2:
              this.modalSkillInfo.target_range = "全体"
              break;
          };

          $('#modal-skill-confirm').modal('show');
        }
      },
      postLearnSkillData(modalSkillInfo) {
        console.log(`postLearnSkillData: ${modalSkillInfo.skill_id} -------`);
        // スキルポイントが1以上ある場合のみ、処理を行う
        if (this.partiesInformation[this.status.currentSelectedPartyMemberIndex].freely_skill_point > 0) {
          // axios.postで登録
          axios.post(`/api/game/rpg/status/skill/learn`,{
            party_id: this.partiesInformation[this.status.currentSelectedPartyMemberIndex].party_id,
            skill_id: modalSkillInfo.skill_id
          })
          .then(response => {
            console.log(`通信OK`);
            console.log(response.data.message);
            this.successSkillMessage = 'スキルの取得が正常に完了しました！';

            // 振り分け後のデータ再取得
            this.updatePartiesInformation();

            // stateを'skill'に戻し、modalを閉じる
            this.$store.dispatch('setMenuStatusStatus', 'skill');
            $('#modal-skill-confirm').modal('hide');
          })
          .catch(error => {
            console.log(`通信失敗。`);
            if (error.response && error.response.data) {
              this.modalErrorMessage = error.response.data.message;
            } else {
              this.modalErrorMessage = "予期しないエラーが発生しました。画面リロードをお試しください。"
            }
            console.log(this.modalErrorMessage);
          });
        } else {
          console.log('スキルポイントなし');
          this.modalErrorMessage = 'スキルポイントが足りません。';
        }
      },
      updatePartiesInformation() {
        // 状態を変更し、要素が無くなっている間のエラーを予防する
        this.$store.dispatch('setMenuStatusStatus', 'updating');

        // ステータスorスキル振り分け後のデータを改めて格納
        axios.get('/api/game/rpg/parties/information')
          .then(response => {
            this.partiesInformation = response.data;
            this.statusArray = [];
            this.skillTreeArray = [];
            this.partiesInformation.forEach(partyInformation => {
              this.statusArray.push(partyInformation['status']);
              this.skillTreeArray.push(partyInformation['skill_tree']);
            });
          }
        );
      },


    }
  }
</script>
