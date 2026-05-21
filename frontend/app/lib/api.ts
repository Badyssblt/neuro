import createClient from 'openapi-fetch'
import type { paths } from './api-schema'

const PUBLIC_AUTH_PATHS = ['/api/login', '/api/register']

let client: ReturnType<typeof createClient<paths>> | null = null

export const useApi = () => {
  if (client) return client

  const config = useRuntimeConfig()
  const baseUrl = (import.meta.server ? config.apiBase : config.public.apiBase) as string

  client = createClient<paths>({
    baseUrl,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  })

  client.use({
    onRequest({ request }) {
      const token = useCookie<string | null>('auth_token').value
      if (token) request.headers.set('Authorization', `Bearer ${token}`)
      return request
    },
    onResponse({ request, response }) {
      if (response.status !== 401) return response

      const url = new URL(request.url)
      if (PUBLIC_AUTH_PATHS.some((p) => url.pathname.startsWith(p))) {
        return response
      }

      const token = useCookie<string | null>('auth_token')
      const user = useState<unknown | null>('auth:user')
      token.value = null
      user.value = null

      if (import.meta.client) {
        navigateTo('/login')
      }
      return response
    },
  })

  return client
}
