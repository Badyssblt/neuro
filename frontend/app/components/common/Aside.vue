<template>
<aside class="border-r p-3.5 flex flex-col justify-between">
    <div>
        <h1 class="font-medium text-lg">Neuro</h1>

        <div class="flex flex-col gap-4 mt-4">
        <div>
            <Button variant="secondary" class="w-full flex justify-start"><NuxtLink class="flex justify-between w-full items-center" to="/dashboard/intent"><LucidePlus/> Nouvelle intention <div class="border border-primary/20 rounded px-2"><Kbd>⌘ I</Kbd></div></NuxtLink></Button>
        </div>
        <div>
            <AsideItem to="/" :icon="LucideHouse">Accueil</AsideItem>
            <AsideItem to="/learnings" :icon="LucideCompass">Parcours en cours</AsideItem>
            <AsideItem to="/dashboard/library" :icon="LucideLayers">Bibliothèque</AsideItem>
        </div>
        
    </div>
    </div>
    
    <div>
        <p>{{ user?.email }}</p>
    </div>
</aside>
</template>


<script setup lang="ts">
import { LucideClock, LucideCompass, LucideHouse, LucideLayers, LucidePlus } from 'lucide-vue-next';
import AsideItem from './AsideItem.vue';
const { user, isAuthenticated } = useAuth()
</script>