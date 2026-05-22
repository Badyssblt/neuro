import { authService } from '~/services/auth'

export type AuthUser = {
  id?: string
  email?: string
  roles?: string[]
}

export const useAuth = () => {
  const user = useState<AuthUser | null>('auth:user', () => null)
  const token = useCookie<string | null>('auth_token', {
    sameSite: 'lax',
    secure: true,
    maxAge: 60 * 60 * 24 * 7,
  })

  const isAuthenticated = computed(() => user.value !== null)

  const fetchMe = async () => {
    if (!token.value) {
      user.value = null
      return null
    }
    const { data, error } = await authService.me()
    if (error || !data) {
      user.value = null
      return null
    }
    user.value = data
    return data
  }

  const login = async (email: string, password: string) => {
    const result = await authService.login(email, password)
    if (result.error) return result
    token.value = result.data?.token ?? null
    console.log(token.value);
    
    await fetchMe()
    return result
  }

  const register = async (email: string, password: string) => {
    const result = await authService.register(email, password)
    if (result.error) return result
    const data = result.data as { token?: string } | undefined
    token.value = data?.token ?? null
    await fetchMe()
    return result
  }

  const logout = async () => {
    token.value = null
    user.value = null
    await navigateTo('/login')
  }

  return {
    user: readonly(user),
    token: readonly(token),
    isAuthenticated,
    login,
    register,
    logout,
    fetchMe,
  }
}
