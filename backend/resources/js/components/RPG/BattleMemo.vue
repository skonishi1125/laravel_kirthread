<script setup>
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
  id: { type: String, default: 'battle' },     // ローカル保存のキーに使う
  title: { type: String, default: '戦闘メモ' },
  placeholder: { type: String, default: 'メモを書く…' },
  rows: { type: Number, default: 10 },          // テキストエリアの行数
  autosave: { type: Boolean, default: true },   // 自動保存ON/OFF
})

const content = ref('')
const key = (k) => `memo:${props.id}:${k}`

// 初期ロード
onMounted(() => {
  try {
    const savedText = localStorage.getItem(key('text'))
    if (savedText !== null) content.value = savedText
  } catch {}
})

// 自動保存（軽量）
watch(content, (v) => {
  if (!props.autosave) return
  try { localStorage.setItem(key('text'), v) } catch {}
})

// 便利操作
const clearMemo = () => {
  if (!confirm('メモをクリアします。よろしいですか？')) return
  content.value = ''
  try { localStorage.removeItem(key('text')) } catch {}
}

const downloadTxt = () => {
  const blob = new Blob([content.value ?? ''], { type: 'text/plain;charset=utf-8' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `${props.id}-memo.txt`
  a.click()
  URL.revokeObjectURL(url)
}
</script>

<template>
  <div class="memo-card">
    <div class="memo-header">
      <span class="memo-title">{{ title }}</span>
      <div class="memo-actions">
        <!-- <button type="button" class="btn btn-sm btn-outline-secondary" @click="downloadTxt">DL</button>
        <button type="button" class="btn btn-sm btn-outline-danger" @click="clearMemo">Clear</button> -->
      </div>
    </div>
    <textarea
      class="memo-textarea form-control"
      v-model="content"
      :rows="rows"
      :placeholder="placeholder"
    />
  </div>
</template>

<style scoped>
.memo-card {
  background: #fffdf2;
  border: 1px solid #e6d59c;
  border-radius: 10px;
  box-shadow: 0 6px 18px rgba(0,0,0,.08);
  padding: 8px;
  height: 72%;            /* 親のcol高さに合わせやすい */
  display: flex;
  flex-direction: column;
}
.memo-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 4px 6px 8px;
  border-bottom: 1px solid #e6d59c;
  margin-bottom: 8px;
}
.memo-title { font-weight: 700; }
.memo-actions > .btn + .btn { margin-left: 6px; }
.memo-textarea {
  background: transparent;
  flex: 1 1 auto;
  resize: none;
  line-height: 1.4;
}
</style>
