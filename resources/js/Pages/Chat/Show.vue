<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import {ref} from "vue";

const body = ref('');

defineProps({
    chat: {
        type: Object
    },
    users: {
        type: Object
    }
})

function storeMessage(chatId) {
    axios.post(route('messages.store'), {'chat_id': chatId, 'body': body.value})
        .then(res => {
            console.log(res);
        })
}

</script>

<template>
    <MainLayout>
        <div class="flex">
            <div class="w-3/4 border border-gray-400 p-4 mr-4 bg-white">
                <div>
                    <h3 class="mb-3">{{ chat.title ?? 'Your chat' }}</h3>
                    <p class="mb-3">Send message</p>
                </div>
                <div>
                    <div class="mb-4">
                        <input placeholder="message" class="rounded-full" type="text" v-model="body">
                    </div>
                    <div>
                        <button @click.prevent="storeMessage(chat.id)" class="text-white bg-indigo-600 rounded-full p-2 hover:bg-amber-600">Message</button>
                    </div>
                </div>
            </div>
            <div class="w-1/4 border border-gray-400 p-4 bg-white">
                <h3 class="text-xl text-sky-500">Users</h3>
                <div v-if="users">
                    <div v-for="user in users" class="mb-4 p-4 flex items-center border-b border-gray-400">
                        <p class="text-lg mr-2">{{ user.id }}</p>
                        <p class="text-lg mr-2">{{ user.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>

</style>
