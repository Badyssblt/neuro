<template>
  <Progress :model-value="progress" />
</template>

<script setup lang="ts">
import type { LearningGoal } from '~/services/learningGoals'
import { Progress } from '@/components/ui/progress'

const props = defineProps<{
  goal: LearningGoal
}>()

const progress = computed(() => (props.goal as { progress?: number }).progress ?? 0)
</script>
