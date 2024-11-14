<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import {router, usePage} from "@inertiajs/vue3";
import {Link} from "@inertiajs/vue3";
import {ref} from "vue";

const isGroup = ref(false);
const title = ref(null);
const usersIds = ref([]);
const page = usePage();

const props = defineProps({
    users: {
        required: true,
        type: Object
    },
    chats: {
        type: Object,
        required: true
    }
})

function createChat(id) {
    router.post(route('chats.store'), {
        users: [id],
        title: title.value
    });
}

function createGroupChat() {
    if (usersIds.value.length < 2) return;
    title.value = title.value ?? 'Your chat';
    router.post(route('chats.store'), {
        users: usersIds.value,
        title: title.value
    });
}

function toggleUsers(id) {
    let index = usersIds.value.indexOf(id);
    if (index === -1) {
        usersIds.value.push(id);
    } else {
        usersIds.value.splice(index, 1);
    }
}

function refreshUserIds() {
    usersIds.value = [];
    isGroup.value = false;
}

Echo.private(`users.${page.props.auth.user.id}`)
    .listen('.notification-message', res => {
        props.chats.filter(chat => {
            if (chat.id === res.chat_id) {
                chat.readableMessageCount = res.count;
                chat.last_message = res.last_message;
            }
        })
    })

</script>

<template>
    <MainLayout>
        <div class="flex items-start">
            <div class="w-1/2 border border-gray-400 p-4 mr-4 bg-white">
                <h3 class="text-2xl text-sky-500">Chats</h3>
                <div v-if="props.chats">
                    <div v-for="chat in props.chats" class="mb-4 p-4 border-b border-gray-400">
                        <Link class="flex" :href="route('chats.show', chat.id)">
                            <p class="text-lg mr-2">{{ chat.id }}</p>
                            <p class="text-lg mr-2">{{ chat.title ?? 'Your chat' }}</p>
                        </Link>
                        <div>
                            <Link :class="['p-4 flex justify-between items-center',
                            chat.readableMessageCount !== 0
                            ? 'bg-sky-50' : '']" :href="route('chats.show', chat.id)">
                                <div class="text-sm">
                                    <p class="text-gray-600">{{ chat.last_message.user_name.name }}</p>
                                    <p class="mb-2 text-gray-500">{{ chat.last_message.body }}</p>
                                    <p class="italic text-gray-400">{{ chat.last_message.createdAt }}</p>
                                </div>
                                <div v-if="chat.readableMessageCount !== 0">
                                    <p class="bg-sky-500 p-1 rounded-full text-white">{{
                                            chat.readableMessageCount
                                        }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/2 border border-gray-400 p-4 bg-white">
                <div class="flex justify-between">
                    <h3 class="text-2xl text-sky-500">Users</h3>
                    <button v-if="!isGroup" @click.prevent="isGroup = true"
                            class="text-white bg-indigo-500 rounded-full p-2 hover:bg-amber-600">Make group
                    </button>
                    <div v-if="isGroup">
                        <input placeholder="group title" type="text" v-model="title" class="rounded-full mr-2">
                        <button v-if="isGroup" @click.prevent="createGroupChat" :class="[
                            'text-white rounded-full p-2 mr-2',
                            usersIds.length < 2 ? 'bg-green-200 disabled' : 'bg-green-500 hover:bg-amber-600'
                        ]">Go chat
                        </button>
                        <button v-if="isGroup" @click.prevent="refreshUserIds"
                                class="text-white bg-indigo-500 rounded-lg p-3 hover:bg-amber-600">X
                        </button>
                    </div>
                </div>
                <div v-if="props.users">
                    <div v-for="user in props.users"
                         class="mb-4 p-4 flex items-center justify-between border-b border-gray-400">
                        <div class="flex items-center">
                            <p class="text-lg mr-2">{{ user.id }}</p>
                            <p class="text-lg mr-2">{{ user.name }}</p>
                            <button @click.prevent="createChat(user.id)"
                                    class="text-white bg-sky-500 rounded-full p-2 hover:bg-amber-600">Message
                            </button>
                        </div>
                        <div v-if="isGroup">
                            <input type="checkbox" class="mr-auto" @click="toggleUsers(user.id)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>

</style>
