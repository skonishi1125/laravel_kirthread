<template>
    <div>
        <input v-model="postId" type="hidden" />
        <input v-model="message" type="text" placeholder="Edit your message" />
        <button @click="updateMessage">Save</button>
        <button @click="testSendingValue">Test</button>
        <p v-if="successMessage">{{ successMessage }}</p>
    </div>
</template>

<script>
export default {
    props: {
        postId: {
            type: String,
            required: true
        },
        initialMessage: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            // postId: this.postId, 
            // ↑propsで宣言したプロパティはdata側で同名で宣言してはいけない。 [Vue warn]: The data property "postId" is already declared as a prop. Use prop default value instead.
            message: this.initialMessage,
            successMessage: ''
        };
    },
    methods: {
        async updateMessage() {
            try {
                const response = await axios.post(`/study/vue/update`, {
                    message: this.message, // $request->input('message')
                    id: this.postId // $request->input('id')として扱えるようになる
                });
                this.successMessage = "Message updated successfully!";
            } catch (error) {
                console.error(error);
                this.successMessage = "Failed to update message.";
            }
        }, // メソッド間はカンマで区切る。

        // 親(blade) -> 子(MessageEditor.vue) -> props, data()などを通して正しく値が受け取れているかのチェック。
        testSendingValue() {
            console.log('Message submitted:', this.message, ' ', this.postId);
            // ダミーのレスポンスメッセージ
            this.responseMessage = 'Message submitted successfully!';
        }
    }
};
</script>
