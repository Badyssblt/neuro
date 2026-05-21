import type { FormContext } from 'vee-validate'

type ApiViolation = { propertyPath: string; message: string }

type ApiErrorShape =
  | { violations: ApiViolation[] }
  | { errors: Record<string, string[]> }
  | { message: string }
  | { detail: string }
  | Record<string, unknown>
  | null
  | undefined

const isStringArray = (value: unknown): value is string[] =>
  Array.isArray(value) && value.every((v) => typeof v === 'string')

export const applyApiErrors = (
  form: Pick<FormContext, 'setFieldError'>,
  error: ApiErrorShape,
): string | null => {
  if (!error || typeof error !== 'object') return 'Une erreur est survenue'

  // API Platform: ConstraintViolation { violations: [{ propertyPath, message }] }
  if ('violations' in error && Array.isArray(error.violations)) {
    for (const v of error.violations) {
      form.setFieldError(v.propertyPath as never, v.message)
    }
    return null
  }

  // Custom Symfony controllers (e.g. AuthController): { errors: { field: string[] } }
  if ('errors' in error && error.errors && typeof error.errors === 'object') {
    for (const [field, messages] of Object.entries(error.errors)) {
      if (isStringArray(messages) && messages.length > 0) {
        form.setFieldError(field as never, messages[0])
      }
    }
    return null
  }

  if ('detail' in error && typeof error.detail === 'string') return error.detail
  if ('message' in error && typeof error.message === 'string') return error.message
  return 'Une erreur est survenue'
}
