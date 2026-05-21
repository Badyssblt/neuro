<template>
    <div>
        <div>
            <h2 class="font-serif text-2xl font-medium">Bibliothèque</h2>
            <p class="opacity-60">Tous vos parcours en un endroit — ce que vous explorez, ce que vous reprendrez, ce que vous avez fini.</p>
        </div>
    </div>
</template>


<script setup lang="ts">


const goals = ref(await useLearningGoals().list())
console.log(goals);

</script>