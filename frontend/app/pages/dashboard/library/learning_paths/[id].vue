<template>
  <div v-if="path" class="flex flex-col gap-8 max-w-3xl">
    <header class="flex flex-col gap-2">
      <h1 class="font-serif text-3xl font-medium">{{ path.title }}</h1>
      <p v-if="path.description" class="opacity-70">{{ path.description }}</p>
      <p v-if="duration" class="text-sm opacity-50">~ {{ duration }} estimées</p>
    </header>

    <ul class="flex flex-col gap-3">
      <li v-for="concept in concepts" :key="concept.id">
        <NuxtLink
          :to="`/dashboard/library/concepts/${concept.id}`"
          class="group flex items-center justify-between gap-4 bg-card border border-foreground/15 rounded-md px-4 py-3 transition-colors hover:border-primary/40 hover:bg-card/70 focus:outline-none focus:border-primary"
        >
          <div class="flex flex-col min-w-0 flex-1">
            <p class="font-medium">{{ concept.title }}</p>
            <p v-if="concept.description" class="text-sm opacity-60 line-clamp-2">
              {{ concept.description }}
            </p>
          </div>

          <span
            class="shrink-0 text-sm font-medium tabular-nums px-2.5 py-1 rounded-full border"
            :class="
              concept.lessonsCompleted === concept.lessonsTotal && (concept.lessonsTotal ?? 0) > 0
                ? 'border-primary text-primary'
                : 'border-foreground/30 opacity-70'
            "
          >
            {{ concept.lessonsCompleted ?? 0 }} / {{ concept.lessonsTotal ?? 0 }} leçons
          </span>

          <LucideChevronRight
            class="shrink-0 opacity-30 group-hover:opacity-100 group-hover:translate-x-0.5 transition"
            :size="20"
          />
        </NuxtLink>
      </li>
    </ul>

    <p v-if="concepts.length === 0" class="opacity-50 italic">
      Aucun concept dans ce parcours pour le moment.
    </p>
  </div>
</template>

<script setup lang="ts">
import { LucideChevronRight } from 'lucide-vue-next'

const route = useRoute()
const id = computed(() => String(route.params.id))

type ConceptItem = {
  id: number
  title: string
  description?: string
  position?: number
  lessonsTotal?: number
  lessonsCompleted?: number
}

type ModuleItem = {
  position?: number
  concepts?: ConceptItem[]
}

type PathDetail = {
  id: number
  title: string
  description?: string
  estimated_duration?: number
  modules?: ModuleItem[]
}

const pathRef = await useLearningPaths().one(id)
const path = computed(() => pathRef.value as PathDetail | null)

const duration = computed(() => {
  const h = path.value?.estimated_duration
  return h ? `${h}h` : null
})

const concepts = computed<ConceptItem[]>(() => {
  const modules = path.value?.modules ?? []
  return [...modules]
    .sort((a, b) => (a.position ?? 0) - (b.position ?? 0))
    .flatMap((m) =>
      [...(m.concepts ?? [])].sort(
        (a, b) => (a.position ?? 0) - (b.position ?? 0),
      ),
    )
})
</script>
