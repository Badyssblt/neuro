<template>
    <div v-if="goal">
        <div>
            <h2 class="font-serif text-xl">{{ goal.title }}</h2>
            <p class="opacity-60">{{ goal.description }}</p>
        </div>
        <LearningPathConstellation :paths="goal.learningPaths"/>
    </div>
</template>

<script setup lang="ts">
import LearningPathConstellation from '~/components/features/LearningPathConstellation.vue';

const id = useRoute().params.id as string
const goal = ref(await useLearningGoals().one(id))
</script>