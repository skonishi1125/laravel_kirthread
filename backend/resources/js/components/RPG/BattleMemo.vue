<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'

const props = defineProps({
  id: { type: String, default: 'battle-memo' },
  initialX: { type: Number, default: 6 },
  initialY: { type: Number, default: 520 },
  placeholder: { type: String, default: 'メモを書く…' },
})

const x = ref(props.initialX)
const y = ref(props.initialY)
const content = ref('')
const memoEl = ref(null)
let dragging = false
let startDx = 0
let startDy = 0

const key = (k) => `memo:${props.id}:${k}`

function onPointerDown(e) {
  dragging = true
  // つかんだ位置と左上の差分を保持
  startDx = e.clientX - x.value
  startDy = e.clientY - y.value

  memoEl.value?.setPointerCapture?.(e.pointerId)
  window.addEventListener('pointermove', onPointerMove)
  window.addEventListener('pointerup', onPointerUp, { once: true })
}

function onPointerMove(e) {
  if (!dragging) return
  // 画面内にクランプ
  const el = memoEl.value
  const maxX = Math.max(0, window.innerWidth - (el?.offsetWidth ?? 0))
  const maxY = Math.max(0, window.innerHeight - (el?.offsetHeight ?? 0))
  x.value = Math.min(Math.max(0, e.clientX - startDx), maxX)
  y.value = Math.min(Math.max(0, e.clientY - startDy), maxY)
}

function onPointerUp() {
  dragging = false
  window.removeEventListener('pointermove', onPointerMove)
  // 位置を保存（簡易永続化：localStorage）
  try {
    localStorage.setItem(key('pos'), JSON.stringify({ x: x.value, y: y.value }))
  } catch {}
}

onMounted(() => {
  try {
    const savedPos = JSON.parse(localStorage.getItem(key('pos')) || 'null')
    if (savedPos) { x.value = savedPos.x ?? x.value; y.value = savedPos.y ?? y.value }
    const savedText = localStorage.getItem(key('text'))
    if (savedText !== null) content.value = savedText
  } catch {}
})

watch(content, (v) => {
  try { localStorage.setItem(key('text'), v) } catch {}
})

onBeforeUnmount(() => window.removeEventListener('pointermove', onPointerMove))
</script>

<template>
  <!-- position: fixed で画面上を自由に移動 -->
  <div
    ref="memoEl"
    class="battle-memo"
    :style="{ left: x + 'px', top: y + 'px' }"
  >
    <div class="memo-header" @pointerdown="onPointerDown">
      <span>戦闘メモ</span>
    </div>
    <textarea
      class="memo-textarea"
      v-model="content"
      :placeholder="placeholder"
    />
  </div>
</template>

<style scoped>
.battle-memo {
  position: fixed;
  z-index: 3000;
  width: 406px;
  min-width: 240px;
  min-height: 160px;
  background: #fffdf2;
  border: 1px solid #e6d59c;
  border-radius: 10px;
  box-shadow: 0 6px 18px rgba(0,0,0,.18);
}
.memo-header {
  padding: 6px 10px;
  font-weight: 600;
  cursor: move;
  user-select: none;
  background: linear-gradient(#fff9d6, #f6e7a8);
  border-bottom: 1px solid #e6d59c;
  border-radius: 10px 10px 0 0;
}
.memo-textarea {
  width: 100%;
  height: 200px;
  resize: both;            /* ユーザーがサイズ変更可 */
  border: none;
  outline: none;
  padding: 10px;
  background: transparent;
  font-family: inherit;
  line-height: 1.4;
}
</style>
