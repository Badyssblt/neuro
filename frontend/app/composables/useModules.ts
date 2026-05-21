import { modulesService, type Module } from '~/services/modules'

const LIST_KEY = (page: number) => `modules:${page}`
const ITEM_KEY = (id: string) => `module:${id}`

export const useModules = () => {
  const list = (page: MaybeRefOrGetter<number> = 1) =>
    useAsyncData(
      () => LIST_KEY(toValue(page)),
      async () => {
        const { data, error } = await modulesService.list(toValue(page))
        if (error) throw error
        return data
      },
      { watch: [() => toValue(page)] },
    )

  const one = (id: MaybeRefOrGetter<string>) =>
    useAsyncData(
      () => ITEM_KEY(toValue(id)),
      async () => {
        const { data, error } = await modulesService.get(toValue(id))
        if (error) throw error
        return data
      },
      { watch: [() => toValue(id)] },
    )

  const refreshList = (page = 1) => refreshNuxtData(LIST_KEY(page))
  const refreshOne = (id: string) => refreshNuxtData(ITEM_KEY(id))

  return {
    list,
    one,
    create: async (body: Module) => {
      const result = await modulesService.create(body)
      if (!result.error) await refreshList()
      return result
    },
    update: async (id: string, body: Partial<Module>) => {
      const result = await modulesService.update(id, body)
      if (!result.error) {
        await refreshOne(id)
        await refreshList()
      }
      return result
    },
    remove: async (id: string) => {
      const result = await modulesService.remove(id)
      if (!result.error) await refreshList()
      return result
    },
  }
}
