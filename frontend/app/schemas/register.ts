import { z } from 'zod'

export const registerSchema = z
  .object({
    email: z.string().min(1, 'Email requis').email('Email invalide'),
    password: z.string().min(8, '8 caractères minimum'),
    passwordConfirm: z.string().min(1, 'Confirmation requise'),
  })
  .refine((data) => data.password === data.passwordConfirm, {
    message: 'Les mots de passe ne correspondent pas',
    path: ['passwordConfirm'],
  })

export type RegisterForm = z.infer<typeof registerSchema>
