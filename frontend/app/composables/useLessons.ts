import { lessonsService, type Lesson } from '~/services/lessons'

const ITEM_KEY = (id: string) => `lesson:${id}`

export const useLessons = () => {
  const one = async (id: MaybeRefOrGetter<string>) => {
    const { data } = await useAsyncData(
      () => ITEM_KEY(toValue(id)),
      async () => {
        const { data, error } = await lessonsService.get(toValue(id))
        if (error) throw error
        return data
      },
      { watch: [() => toValue(id)] },
    )
    return data
  }

  const refreshOne = (id: string) => refreshNuxtData(ITEM_KEY(id))

  return {
    one,
    update: async (id: string, body: Partial<Lesson>) => {
      const result = await lessonsService.update(id, body)
      if (!result.error) await refreshOne(id)
      return result
    },
  }
}
