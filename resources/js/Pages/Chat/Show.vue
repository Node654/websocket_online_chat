<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import {onBeforeUnmount, onMounted, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const body = ref('');
const page = usePage();

const props = defineProps({
    chat: {
        type: Object,
        required: true
    },
    users: {
        type: Object,
        required: true
    },
    messages: {
        type: Object,
        required: true
    }
})

function storeMessage() {
    axios.post(route('messages.store'), {'chat_id': props.chat.id, 'body': body.value})
        .then(res => {
            props.messages.push(res.data);
            body.value = '';
        })
}

Echo.channel(`store-message.${props.chat.id}`)
    .listen('.store-message', res => {
        props.messages.push(res.message);
        if (page.url === `/chats/${props.chat.id}`) {
            axios.patch(route('messages.update'), {
                chat_id: res.message.chat_id,
                message_id: res.message.id,
                user_id: page.props.auth.user.id
            }).then(res => {
            })
        }
    });
</script>

<style scoped>

</style>

<template>
    <MainLayout>
        <div class="flex items-start">
            <div class="w-3/4 border border-gray-400 p-4 mr-4 bg-white">
                <div class="mb-5">
                    <h2 class="mb-5">{{ props.chat.title ?? 'Your chat' }}</h2>
                    <div v-for="message in props.messages" :class="message.is_owner ? 'text-right' : '' ">
                        <div
                            :class="['inline-block mb-4', message.is_owner ? 'text-left bg-green-100 p-3' : 'bg-sky-100 p-3']">
                            <h3 class="mb-1">{{ message.user_name.name }}</h3>
                            <p class="mb-3"><strong>{{ message.body }}</strong></p>
                            <p>{{ message.createdAt }}</p>
                        </div>
                    </div>
                </div>
                <p class="mb-3">Send message</p>
                <div>
                    <div class="mb-4">
                        <input placeholder="message" class="rounded-full" type="text" v-model="body">
                    </div>
                    <div>
                        <button @click.prevent="storeMessage"
                                class="text-white bg-indigo-600 rounded-full p-2 hover:bg-amber-600">Message
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-1/4 border border-gray-400 p-4 bg-white">
                <h3 class="text-xl text-sky-500">Users</h3>
                <div v-if="props.users">
                    <div v-for="user in props.users" class="mb-4 p-4 flex items-center border-b border-gray-400">
                        <p class="text-lg mr-2">{{ user.id }}</p>
                        <p class="text-lg mr-2">{{ user.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
