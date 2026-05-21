<template>
  <form
    @submit="onSubmit"
    class="flex flex-col gap-4 bg-card flex-1 p-6 border border-primary/20 w-96 mx-auto mt-50 rounded-md"
  >
    <FormField v-slot="{ componentField, errors }" name="email">
      <FormItem>
        <FormLabel class="uppercase text-sm opacity-60">Email</FormLabel>
        <FormControl>
          <InputGroup>
            <InputGroupInput
              v-bind="componentField"
              placeholder="vous@example.com"
              type="email"
              autocomplete="email"
              :aria-invalid="errors.length > 0"
            />
            <InputGroupAddon>
              <LucideMail />
            </InputGroupAddon>
          </InputGroup>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <FormField v-slot="{ componentField, errors }" name="password">
      <FormItem>
        <FormLabel class="uppercase text-sm opacity-60">Mot de passe</FormLabel>
        <FormControl>
          <InputGroup>
            <InputGroupInput
              v-bind="componentField"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="new-password"
              :aria-invalid="errors.length > 0"
            />
            <InputGroupAddon>
              <LucideLock />
            </InputGroupAddon>
            <InputGroupAddon align="inline-end">
              <Button
                type="button"
                variant="ghost"
                @click="showPassword = !showPassword"
                :aria-label="showPassword ? 'Masquer' : 'Afficher'"
              >
                <component :is="showPassword ? LucideEyeOff : LucideEye" />
              </Button>
            </InputGroupAddon>
          </InputGroup>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <FormField v-slot="{ componentField, errors }" name="passwordConfirm">
      <FormItem>
        <FormLabel class="uppercase text-sm opacity-60">Confirmation</FormLabel>
        <FormControl>
          <InputGroup>
            <InputGroupInput
              v-bind="componentField"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="new-password"
              :aria-invalid="errors.length > 0"
            />
            <InputGroupAddon>
              <LucideLock />
            </InputGroupAddon>
          </InputGroup>
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>

    <p v-if="formError" role="alert" class="text-destructive text-sm">
      {{ formError }}
    </p>

    <Button type="submit" :disabled="isSubmitting">
      <template v-if="isSubmitting">Création…</template>
      <template v-else>Créer un compte <LucideArrowRight /></template>
    </Button>

    <p class="text-sm text-center opacity-60">
      Déjà un compte ?
      <NuxtLink to="/login" class="underline">Se connecter</NuxtLink>
    </p>
  </form>
</template>

<script setup lang="ts">
import { toTypedSchema } from '@vee-validate/zod'
import { useForm } from 'vee-validate'
import { LucideArrowRight, LucideEye, LucideEyeOff, LucideLock, LucideMail } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group'
import { registerSchema } from '~/schemas/register'
import { applyApiErrors } from '~/lib/apiErrors'

definePageMeta({ layout: 'empty', middleware: 'guest' })

const { register } = useAuth()
const showPassword = ref(false)
const formError = ref<string | null>(null)

const form = useForm({
  validationSchema: toTypedSchema(registerSchema),
  initialValues: { email: '', password: '', passwordConfirm: '' },
})

const isSubmitting = form.isSubmitting

const onSubmit = form.handleSubmit(async ({ email, password }) => {
  formError.value = null
  const { error } = await register(email, password)

  if (error) {
    formError.value = applyApiErrors(form, error)
    return
  }

  await navigateTo('/')
})
</script>
