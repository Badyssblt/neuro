<template>
    <Progress :model-value="progress" />
</template>

<script setup lang="ts">
import type { LearningGoal } from '~/services/learningGoals';
import { Progress } from '@/components/ui/progress'

const props = defineProps<{
    goal: LearningGoal
}>()

const max = computed(() => props.goal.learningPaths?.length)
const current = computed(() => props.goal.learningPaths?.filter(path => path.completed).length)

const progress = computed(() => {
    if (!max.value || !current.value) return 0
    return Math.round((current.value / max.value) * 100)
})
</script>