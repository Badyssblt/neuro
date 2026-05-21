import { useApi } from '~/lib/api'
import type { components } from '~/lib/api-schema'

export type Module = components['schemas']['Modules']

export const modulesService = {
  list: (page = 1) =>
    useApi().GET('/api/modules', { params: { query: { page } } }),

  get: (id: string) =>
    useApi().GET('/api/modules/{id}', { params: { path: { id } } }),

  create: (body: Module) =>
    useApi().POST('/api/modules', { body }),

  update: (id: string, body: Partial<Module>) =>
    useApi().PATCH('/api/modules/{id}', {
      params: { path: { id } },
      body,
      headers: { 'Content-Type': 'application/merge-patch+json' },
    }),

  remove: (id: string) =>
    useApi().DELETE('/api/modules/{id}', { params: { path: { id } } }),
}
