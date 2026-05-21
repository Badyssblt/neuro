import { useApi } from '~/lib/api'

export const authService = {
  login: (email: string, password: string) =>
    useApi().POST('/api/login', { body: { email, password } }),

  register: (email: string, password: string) =>
    useApi().POST('/api/register', { body: { email, password } }),

  me: () => useApi().GET('/api/me'),
}
