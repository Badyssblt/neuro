<script setup lang="ts">
import Aside from '~/components/common/Aside.vue';

</script>

<template>
    <div>
        <div class="flex h-screen">
            <Aside class="w-72"/>
            <main class="flex-1 p-4">
                <slot />
            </main>
        </div>
    </div>
</template> 