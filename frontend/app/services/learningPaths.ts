import { useApi } from '~/lib/api'
import type { components } from '~/lib/api-schema'

export type LearningPath = components['schemas']['LearningPath']

export const learningPathsService = {
  list: (page = 1) =>
    useApi().GET('/api/learning_paths', { params: { query: { page } } }),

  get: (id: string) =>
    useApi().GET('/api/learning_paths/{id}', { params: { path: { id } } }),

  create: (body: LearningPath) =>
    useApi().POST('/api/learning_paths', { body }),

  update: (id: string, body: Partial<LearningPath>) =>
    useApi().PATCH('/api/learning_paths/{id}', {
      params: { path: { id } },
      body,
      headers: { 'Content-Type': 'application/merge-patch+json' },
    }),

  remove: (id: string) =>
    useApi().DELETE('/api/learning_paths/{id}', { params: { path: { id } } }),
}
