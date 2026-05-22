import createClient from 'openapi-fetch'
import type { paths } from './api-schema'

const PUBLIC_AUTH_PATHS = ['/api/login', '/api/register']

export const useApi = () => {
  const config = useRuntimeConfig()
  const baseUrl = (import.meta.server ? config.apiBase : config.public.apiBase) as string
  const token = useCookie<string | null>('auth_token')
  const user = useState<unknown | null>('auth:user')

  const client = createClient<paths>({
    baseUrl,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  })

  client.use({
    onRequest({ request }) {      
      if (token.value) request.headers.set('Authorization', `Bearer ${token.value}`)
        
      return request
    },
    onResponse({ request, response }) {
      if (response.status !== 401) return response
      console.log(response.status);
      
      const url = new URL(request.url)
      if (PUBLIC_AUTH_PATHS.some((p) => url.pathname.startsWith(p))) {
        return response
      }

      if (import.meta.client) {
        token.value = null
        user.value = null
        navigateTo('/login')
      }
    
      return response
    },
  })

  return client
}

