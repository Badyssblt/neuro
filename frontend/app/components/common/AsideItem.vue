<template>
    <NuxtLink :to="to" class="flex gap-2 items-center opacity-70 hover:bg-foreground/10 p-1 rounded-md text-sm transition-all hover:opacity-100">
        <component :is="icon" class="size-5"/>
        <div class="pt-1">
            <slot />
        </div>
    </NuxtLink>
</template> 


<script setup lang="ts">
defineProps<{
    to: string,
    icon: Component
}>()
</script>