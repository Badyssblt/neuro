import { useApi } from '~/lib/api'
import type { components } from '~/lib/api-schema'

export type Lesson = components['schemas']['Lessons']

export const lessonsService = {
  list: (page = 1) =>
    useApi().GET('/api/lessons', { params: { query: { page } } }),

  get: (id: string) =>
    useApi().GET('/api/lessons/{id}', { params: { path: { id } } }),

  update: (id: string, body: Partial<Lesson>) =>
    useApi().PATCH('/api/lessons/{id}', {
      params: { path: { id } },
      body,
      headers: { 'Content-Type': 'application/merge-patch+json' },
    }),
}
