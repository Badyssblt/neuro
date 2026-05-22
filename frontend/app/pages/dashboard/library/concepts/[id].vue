<template>
  <div v-if="concept" class="flex flex-col gap-10 max-w-3xl">
    <header class="flex flex-col gap-3">
      <div class="flex items-center gap-3">
        <span
          v-if="concept.difficulty"
          class="text-xs uppercase tracking-wider px-2 py-0.5 rounded-full border"
          :class="difficultyClass"
        >
          {{ concept.difficulty }}
        </span>
        <span v-if="concept.estimated_time" class="text-xs opacity-50">
          ~ {{ concept.estimated_time }} min
        </span>
      </div>

      <h1 class="font-serif text-4xl font-medium leading-tight">
        {{ concept.title }}
      </h1>

      <p v-if="concept.description" class="opacity-70 leading-relaxed">
        {{ concept.description }}
      </p>

      <div v-if="lessons.length > 0" class="flex items-center gap-3 mt-2">
        <div class="flex-1 h-1 rounded-full bg-foreground/10 overflow-hidden">
          <div
            class="h-full bg-primary transition-all"
            :style="{ width: `${progressPercent}%` }"
          />
        </div>
        <span class="text-sm tabular-nums opacity-60">
          {{ completedCount }} / {{ lessons.length }}
        </span>
      </div>
    </header>

    <ol class="flex flex-col gap-2">
      <li v-for="(lesson, i) in lessons" :key="lesson.id">
        <NuxtLink
          :to="`/dashboard/library/lessons/${lesson.id}`"
          class="group flex items-start gap-4 p-4 rounded-md border transition-colors"
          :class="
            lesson.completed
              ? 'border-primary/30 bg-primary/5 hover:border-primary/60'
              : 'border-foreground/15 bg-card hover:border-primary/40'
          "
        >
          <div
            class="shrink-0 w-9 h-9 rounded-full border-2 flex items-center justify-center"
            :class="
              lesson.completed
                ? 'bg-primary border-primary text-primary-foreground'
                : 'border-foreground/30 text-foreground/40 group-hover:border-primary/60 group-hover:text-primary'
            "
          >
            <LucideCheck v-if="lesson.completed" :size="18" :stroke-width="3" />
            <span v-else class="text-xs font-medium tabular-nums">
              {{ String(i + 1).padStart(2, '0') }}
            </span>
          </div>

          <div class="flex-1 min-w-0">
            <p class="font-medium">{{ lesson.title }}</p>
            <p v-if="lesson.summary" class="text-sm opacity-60 line-clamp-2 mt-0.5">
              {{ lesson.summary }}
            </p>
          </div>

          <LucideChevronRight
            class="shrink-0 opacity-30 group-hover:opacity-100 group-hover:translate-x-0.5 transition mt-2"
            :size="20"
          />
        </NuxtLink>
      </li>
    </ol>

    <p v-if="lessons.length === 0" class="opacity-50 italic">
      Aucune leçon pour ce concept.
    </p>
  </div>
</template>

<script setup lang="ts">
import { LucideCheck, LucideChevronRight } from 'lucide-vue-next'

const route = useRoute()
const id = computed(() => String(route.params.id))

type LessonItem = {
  id: number
  title: string
  summary?: string
  position?: number
  completed?: boolean
}

type ConceptDetail = {
  id: number
  title: string
  description?: string
  difficulty?: string
  estimated_time?: number
  lessons?: LessonItem[]
}

const conceptRef = await useConcepts().one(id)
const concept = computed(() => conceptRef.value as ConceptDetail | null)

const lessons = computed<LessonItem[]>(() =>
  [...(concept.value?.lessons ?? [])].sort(
    (a, b) => (a.position ?? 0) - (b.position ?? 0),
  ),
)

const completedCount = computed(
  () => lessons.value.filter((l) => l.completed).length,
)

const progressPercent = computed(() =>
  lessons.value.length === 0
    ? 0
    : Math.round((completedCount.value / lessons.value.length) * 100),
)

const difficultyClass = computed(() => {
  switch (concept.value?.difficulty) {
    case 'beginner':
      return 'border-[var(--d-beg)] text-[var(--d-beg)]'
    case 'intermediate':
      return 'border-[var(--d-int)] text-[var(--d-int)]'
    case 'advanced':
      return 'border-[var(--d-adv)] text-[var(--d-adv)]'
    default:
      return 'border-foreground/30 opacity-60'
  }
})
</script>
