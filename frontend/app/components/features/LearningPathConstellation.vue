<template>
  <TooltipProvider :delay-duration="200">
    <div class="w-full overflow-x-auto">
      <div class="relative" :style="{ width: `${width}px`, height: `${height}px` }">
        <svg
          :viewBox="`0 0 ${width} ${height}`"
          :width="width"
          :height="height"
          class="absolute inset-0"
          role="img"
          :aria-label="`Constellation de ${paths.length} parcours`"
        >
          <line
            v-for="edge in edges"
            :key="edge.id"
            :x1="edge.x1"
            :y1="edge.y1"
            :x2="edge.x2"
            :y2="edge.y2"
            :stroke="edge.color"
            :stroke-width="edge.width"
            :stroke-dasharray="edge.dash"
            stroke-linecap="round"
            :class="edge.animate ? 'constellation-edge-animated' : ''"
          />
        </svg>

        <div
          v-for="node in nodes"
          :key="node.id"
          class="absolute"
          :style="{
            left: `${node.x}px`,
            top: `${node.y}px`,
            width: `${NODE_RADIUS * 2}px`,
            height: `${NODE_RADIUS * 2}px`,
            transform: 'translate(-50%, -50%)',
          }"
        >
          <span
            class="absolute left-1/2 -translate-x-1/2 whitespace-nowrap text-sm font-medium"
            :style="{ bottom: `calc(100% + 10px)` }"
          >
            {{ truncate(node.title) }}
          </span>

          <Tooltip>
            <TooltipTrigger as-child>
              <NuxtLink
                :to="`/dashboard/library/learning_paths/${node.id}`"
                class="block w-full h-full rounded-full border-2 flex items-center justify-center transition-transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary/60"
                :class="[
                  node.completed
                    ? 'bg-primary border-primary'
                    : node.current
                      ? 'bg-card border-primary'
                      : 'bg-card border-foreground/30',
                ]"
                :aria-label="node.title"
              >
                <LucideCheck v-if="node.completed" class="text-primary-foreground" :size="22" :stroke-width="3" />
              </NuxtLink>
            </TooltipTrigger>
            <TooltipContent side="bottom">{{ node.title }}</TooltipContent>
          </Tooltip>
        </div>
      </div>
    </div>
  </TooltipProvider>
</template>

<script setup lang="ts">
import { LucideCheck } from 'lucide-vue-next'
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip'

type PathNode = {
  id: number | string
  title: string
  completed?: boolean
}

const props = defineProps<{
  paths: PathNode[]
}>()

const NODE_RADIUS = 22
const SPACING_X = 165
const AMPLITUDE_Y = 48
const PADDING_X = 72
const PADDING_TOP = 56
const PADDING_BOTTOM = 36

const width = computed(
  () => PADDING_X * 2 + Math.max(0, props.paths.length - 1) * SPACING_X,
)
const height = computed(() => PADDING_TOP + AMPLITUDE_Y * 2 + PADDING_BOTTOM)

const firstIncompleteIndex = computed(() =>
  props.paths.findIndex((p) => !p.completed),
)

const nodes = computed(() =>
  props.paths.map((path, i) => {
    const offset = i === 0 ? 0 : i % 2 === 1 ? -AMPLITUDE_Y : AMPLITUDE_Y
    return {
      id: path.id,
      title: path.title,
      completed: !!path.completed,
      current: !path.completed && i === firstIncompleteIndex.value,
      x: PADDING_X + i * SPACING_X,
      y: PADDING_TOP + AMPLITUDE_Y + offset,
    }
  }),
)

const edges = computed(() => {
  const result: {
    id: string
    x1: number
    y1: number
    x2: number
    y2: number
    color: string
    width: number
    dash: string | undefined
    animate: boolean
  }[] = []

  for (let i = 0; i < nodes.value.length - 1; i++) {
    const a = nodes.value[i]!
    const b = nodes.value[i + 1]!

    const bothCompleted = a.completed && b.completed
    const touchesCurrent = a.current || b.current
    const dimmed = !bothCompleted && !touchesCurrent

    result.push({
      id: `${a.id}-${b.id}`,
      x1: a.x,
      y1: a.y,
      x2: b.x,
      y2: b.y,
      color: dimmed ? 'var(--border)' : 'var(--primary)',
      width: bothCompleted ? 2 : 1.5,
      dash: bothCompleted ? undefined : '5 6',
      animate: touchesCurrent,
    })
  }

  return result
})

const truncate = (s: string, max = 18) =>
  s.length > max ? s.slice(0, max - 1) + '…' : s
</script>

<style scoped>
.constellation-edge-animated {
  animation: constellation-dash 1.8s linear infinite;
}

@keyframes constellation-dash {
  to {
    stroke-dashoffset: -22;
  }
}
</style>
