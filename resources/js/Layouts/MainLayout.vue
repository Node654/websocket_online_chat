<script setup>
import {Link} from "@inertiajs/vue3";
</script>

<template>
    <div class="p-4 w-3/4 mx-auto">
        <div class="mb-5">
            <Link :href="route('chats.index')" class="text-sky-500 text-2xl">
                Chats
            </Link>
        </div>
        <slot/>
    </div>
</template>

<style scoped>

</style>
