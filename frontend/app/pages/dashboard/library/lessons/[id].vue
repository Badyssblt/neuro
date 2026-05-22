<template>
  <article v-if="lesson" class="flex flex-col gap-8 max-w-3xl">
    <header class="flex flex-col gap-3">
      <div class="flex items-center gap-2 text-xs uppercase tracking-wider opacity-50">
        <span>Leçon</span>
        <span v-if="lesson.position">· {{ String(lesson.position).padStart(2, '0') }}</span>
      </div>

      <h1 class="font-serif text-4xl font-medium leading-tight">
        {{ lesson.title }}
      </h1>

      <p v-if="lesson.summary" class="text-lg opacity-70 leading-relaxed border-l-2 border-primary/40 pl-4">
        {{ lesson.summary }}
      </p>
    </header>

    <div class="lesson-prose" v-html="contentHtml" />

    <section v-if="lesson.examples" class="bg-card border border-foreground/15 rounded-lg p-5 flex flex-col gap-3">
      <h2 class="font-serif text-lg font-medium text-primary">Exemples</h2>
      <div class="lesson-prose lesson-prose-sm" v-html="examplesHtml" />
    </section>

    <footer class="flex items-center justify-between pt-4 border-t border-foreground/10">
      <Button
        :variant="lesson.completed ? 'secondary' : 'default'"
        :disabled="toggling"
        @click="toggleCompleted"
      >
        <LucideCheck v-if="lesson.completed" :size="16" :stroke-width="3" />
        {{ lesson.completed ? 'Marquée comme faite' : 'Marquer comme faite' }}
      </Button>
    </footer>
  </article>
</template>

<script setup lang="ts">
import { marked } from 'marked'
import { LucideCheck } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'

const route = useRoute()
const id = computed(() => String(route.params.id))

type LessonDetail = {
  id: number
  title: string
  content?: string
  summary?: string
  examples?: string
  position?: number
  completed?: boolean
}

const { one, update } = useLessons()
const lessonRef = await one(id)
const lesson = computed(() => lessonRef.value as LessonDetail | null)

marked.setOptions({ breaks: true, gfm: true })

const contentHtml = computed(() =>
  lesson.value?.content ? marked.parse(lesson.value.content) : '',
)
const examplesHtml = computed(() =>
  lesson.value?.examples ? marked.parse(lesson.value.examples) : '',
)

const toggling = ref(false)
const toggleCompleted = async () => {
  if (!lesson.value || toggling.value) return
  toggling.value = true
  try {
    await update(id.value, { completed: !lesson.value.completed })
  } finally {
    toggling.value = false
  }
}
</script>

<style scoped>
.lesson-prose {
  line-height: 1.7;
  color: var(--foreground);
}

.lesson-prose :deep(h1),
.lesson-prose :deep(h2),
.lesson-prose :deep(h3) {
  font-family: 'Source Serif 4', ui-serif, Georgia, serif;
  font-weight: 500;
  margin-top: 1.5em;
  margin-bottom: 0.5em;
  line-height: 1.3;
}

.lesson-prose :deep(h1) { font-size: 1.875rem; }
.lesson-prose :deep(h2) { font-size: 1.5rem; }
.lesson-prose :deep(h3) { font-size: 1.25rem; color: var(--accent); }

.lesson-prose :deep(p) {
  margin-bottom: 1em;
}

.lesson-prose :deep(ul),
.lesson-prose :deep(ol) {
  margin: 1em 0;
  padding-left: 1.5em;
}

.lesson-prose :deep(ul) { list-style: disc; }
.lesson-prose :deep(ol) { list-style: decimal; }

.lesson-prose :deep(li) {
  margin: 0.35em 0;
}

.lesson-prose :deep(code) {
  background: var(--surface-2);
  padding: 0.15em 0.4em;
  border-radius: 0.25rem;
  font-size: 0.9em;
  font-family: ui-monospace, 'JetBrains Mono', Menlo, monospace;
  color: var(--accent);
}

.lesson-prose :deep(pre) {
  background: var(--bg-2);
  border: 1px solid var(--border);
  padding: 1em 1.15em;
  border-radius: 0.5rem;
  overflow-x: auto;
  margin: 1em 0;
  line-height: 1.5;
}

.lesson-prose :deep(pre code) {
  background: transparent;
  padding: 0;
  color: var(--foreground);
}

.lesson-prose :deep(blockquote) {
  border-left: 3px solid var(--accent);
  padding-left: 1em;
  margin: 1em 0;
  opacity: 0.85;
  font-style: italic;
}

.lesson-prose :deep(strong) {
  font-weight: 600;
  color: var(--foreground);
}

.lesson-prose :deep(a) {
  color: var(--primary);
  text-decoration: underline;
  text-underline-offset: 2px;
}

.lesson-prose-sm {
  font-size: 0.95rem;
  line-height: 1.6;
}

.lesson-prose-sm :deep(h1),
.lesson-prose-sm :deep(h2),
.lesson-prose-sm :deep(h3) {
  font-size: 1.05rem;
  margin-top: 0.75em;
}

.lesson-prose-sm :deep(p) {
  margin-bottom: 0.5em;
}
</style>
