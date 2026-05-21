import { useApi } from '~/lib/api'
import type { components } from '~/lib/api-schema'

export type Concept = components['schemas']['Concepts']

export const conceptsService = {
  list: (page = 1) =>
    useApi().GET('/api/concepts', { params: { query: { page } } }),

  get: (id: string) =>
    useApi().GET('/api/concepts/{id}', { params: { path: { id } } }),

  create: (body: Concept) =>
    useApi().POST('/api/concepts', { body }),

  update: (id: string, body: Partial<Concept>) =>
    useApi().PATCH('/api/concepts/{id}', {
      params: { path: { id } },
      body,
      headers: { 'Content-Type': 'application/merge-patch+json' },
    }),

  remove: (id: string) =>
    useApi().DELETE('/api/concepts/{id}', { params: { path: { id } } }),
}
