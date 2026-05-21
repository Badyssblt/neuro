<template>
    <div>
        <div>

        </div>

        <div>
            <p class="font-serif font-medium">{{ goal.title }}</p>
            <p class="font-serif font-medium text-sm opacity-60">{{ goal.description }}</p>
        </div>
    </div>
</template> 

<script setup lang="ts">
import type { LearningGoal } from '~/services/learningGoals';

defineProps<{
    goal: LearningGoal
}>()
</script>