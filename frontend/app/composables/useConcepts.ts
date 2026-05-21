import { conceptsService, type Concept } from '~/services/concepts'

const LIST_KEY = (page: number) => `concepts:${page}`
const ITEM_KEY = (id: string) => `concept:${id}`

export const useConcepts = () => {
  const list = (page: MaybeRefOrGetter<number> = 1) =>
    useAsyncData(
      () => LIST_KEY(toValue(page)),
      async () => {
        const { data, error } = await conceptsService.list(toValue(page))
        if (error) throw error
        return data
      },
      { watch: [() => toValue(page)] },
    )

  const one = (id: MaybeRefOrGetter<string>) =>
    useAsyncData(
      () => ITEM_KEY(toValue(id)),
      async () => {
        const { data, error } = await conceptsService.get(toValue(id))
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
    create: async (body: Concept) => {
      const result = await conceptsService.create(body)
      if (!result.error) await refreshList()
      return result
    },
    update: async (id: string, body: Partial<Concept>) => {
      const result = await conceptsService.update(id, body)
      if (!result.error) {
        await refreshOne(id)
        await refreshList()
      }
      return result
    },
    remove: async (id: string) => {
      const result = await conceptsService.remove(id)
      if (!result.error) await refreshList()
      return result
    },
  }
}
