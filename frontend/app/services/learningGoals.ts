import { useApi } from '~/lib/api'
import type { components } from '~/lib/api-schema'

export type LearningGoal = components['schemas']['LearningGoal']

export const learningGoalsService = {
  list: (page = 1) =>
    useApi().GET('/api/learning_goals', { params: { query: { page } } }),

  get: (id: string) =>
    useApi().GET('/api/learning_goals/{id}', { params: { path: { id } } }),

  create: (body: LearningGoal) =>
    useApi().POST('/api/learning_goals', { body }),

  update: (id: string, body: Partial<LearningGoal>) =>
    useApi().PATCH('/api/learning_goals/{id}', {
      params: { path: { id } },
      body,
      headers: { 'Content-Type': 'application/merge-patch+json' },
    }),

  remove: (id: string) =>
    useApi().DELETE('/api/learning_goals/{id}', { params: { path: { id } } }),
}
