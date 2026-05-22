import { learningPathsService, type LearningPath } from '~/services/learningPaths'

const LIST_KEY = (page: number) => `learning_paths:${page}`
const ITEM_KEY = (id: string) => `learning_path:${id}`

export const useLearningPaths = () => {
  const list = async (page: MaybeRefOrGetter<number> = 1) => {
    const { data } = await useAsyncData(
      () => LIST_KEY(toValue(page)),
      async () => {
        const { data, error } = await learningPathsService.list(toValue(page))
        if (error) throw error
        return data
      },
      { watch: [() => toValue(page)] },
    )
    return data
  }

  const one = async (id: MaybeRefOrGetter<string>) => {
    const { data } = await useAsyncData(
      () => ITEM_KEY(toValue(id)),
      async () => {
        const { data, error } = await learningPathsService.get(toValue(id))
        if (error) throw error
        return data
      },
      { watch: [() => toValue(id)] },
    )
    return data
  }

  const refreshList = (page = 1) => refreshNuxtData(LIST_KEY(page))
  const refreshOne = (id: string) => refreshNuxtData(ITEM_KEY(id))

  return {
    list,
    one,
    create: async (body: LearningPath) => {
      const result = await learningPathsService.create(body)
      if (!result.error) await refreshList()
      return result
    },
    update: async (id: string, body: Partial<LearningPath>) => {
      const result = await learningPathsService.update(id, body)
      if (!result.error) {
        await refreshOne(id)
        await refreshList()
      }
      return result
    },
    remove: async (id: string) => {
      const result = await learningPathsService.remove(id)
      if (!result.error) await refreshList()
      return result
    },
  }
}
