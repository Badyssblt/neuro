<template>
    <NuxtLink :to="'/dashboard/library/' + goal.id" class="bg-card rounded-md border border-foreground/30 p-4">
        <div>

        </div>

        <div>
            <p class="font-serif font-medium">{{ goal.title }}</p>
            <p class="font-serif font-medium text-sm opacity-60">{{ goal.description }}</p>
        </div>

        <div>
            <ConceptProgressBar :goal="goal"/>
        </div>
    </NuxtLink>
</template> 

<script setup lang="ts">
import type { LearningGoal } from '~/services/learningGoals';
import ConceptProgressBar from './ConceptProgressBar.vue';

defineProps<{
    goal: LearningGoal
}>()
</script>