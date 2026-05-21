import { learningGoalsService, type LearningGoal } from '~/services/learningGoals'

const LIST_KEY = (page: number) => `learning_goals:${page}`
const ITEM_KEY = (id: string) => `learning_goal:${id}`

export const useLearningGoals = () => {
  const list = (page: MaybeRefOrGetter<number> = 1) =>
    useAsyncData(
      () => LIST_KEY(toValue(page)),
      async () => {
        const { data, error } = await learningGoalsService.list(toValue(page))
        if (error) throw error
        return data
      },
      { watch: [() => toValue(page)] },
    )

  const one = (id: MaybeRefOrGetter<string>) =>
    useAsyncData(
      () => ITEM_KEY(toValue(id)),
      async () => {
        const { data, error } = await learningGoalsService.get(toValue(id))
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
    create: async (body: LearningGoal) => {
      const result = await learningGoalsService.create(body)
      if (!result.error) await refreshList()
      return result
    },
    update: async (id: string, body: Partial<LearningGoal>) => {
      const result = await learningGoalsService.update(id, body)
      if (!result.error) {
        await refreshOne(id)
        await refreshList()
      }
      return result
    },
    remove: async (id: string) => {
      const result = await learningGoalsService.remove(id)
      if (!result.error) await refreshList()
      return result
    },
  }
}
