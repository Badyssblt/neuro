export default defineNuxtRouteMiddleware(async () => {
  const { isAuthenticated, token, fetchMe } = useAuth()

  if (!isAuthenticated.value && token.value) {
    await fetchMe()
  }

  if (isAuthenticated.value) {
    return navigateTo('/')
  }
})
