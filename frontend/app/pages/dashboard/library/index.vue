<template>
    <div>
        <div>
            <h2 class="font-serif text-2xl font-medium">Bibliothèque</h2>
            <p class="opacity-60">Tous vos parcours en un endroit — ce que vous explorez, ce que vous reprendrez, ce que vous avez fini.</p>
        </div>
        <div class="flex flex-wrap" v-if="goals">
            <LearningGoalCard
                v-for="goal in goals"
                :key="goal.id"
                :goal="goal"/>
        </div>
    </div>
</template>


<script setup lang="ts">
import LearningGoalCard from '~/components/features/LearningGoalCard.vue';
import type { LearningGoal } from '~/services/learningGoals';



const goals = await useLearningGoals().list()

</script>