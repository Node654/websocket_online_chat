<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import {router} from "@inertiajs/vue3";
import {Link} from "@inertiajs/vue3";

defineProps({
    users: {
        type: Object
    },
    chats: {
        type: Object
    }
})

function createChat(id) {
    router.post(route('chats.store'), {
        users: [id],
        title: null
    });
}

</script>

<template>
    <MainLayout>
        <div class="flex">
            <div class="w-1/2 border border-gray-400 p-4 mr-4 bg-white">
                <h3 class="text-2xl text-sky-500">Chats</h3>
                <div v-if="chats">
                    <div v-for="chat in chats" class="mb-4 p-4 flex items-center border-b border-gray-400">
                        <Link class="flex" :href="route('chats.show', chat.id)">
                            <p class="text-lg mr-2">{{ chat.id }}</p>
                            <p class="text-lg mr-2">{{ chat.title ?? 'Your chat' }}</p>
                        </Link>
                    </div>
                </div>
            </div>
            <div class="w-1/2 border border-gray-400 p-4 bg-white">
                <h3 class="text-2xl text-sky-500">Users</h3>
                <div v-if="users">
                    <div v-for="user in users" class="mb-4 p-4 flex items-center border-b border-gray-400">
                        <p class="text-lg mr-2">{{ user.id }}</p>
                        <p class="text-lg mr-2">{{ user.name }}</p>
                        <button @click.prevent="createChat(user.id)" class="text-white bg-sky-500 rounded-full p-2 hover:bg-amber-600">Message</button>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>

</style>
