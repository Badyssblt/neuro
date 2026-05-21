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
        <div class="flex justify-between w-full items-end">
          <FormLabel class="uppercase text-sm opacity-60">Mot de passe</FormLabel>
          <NuxtLink class="opacity-60 text-sm">Oublié ?</NuxtLink>
        </div>
        <FormControl>
          <InputGroup>
            <InputGroupInput
              v-bind="componentField"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="current-password"
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

    <p v-if="formError" role="alert" class="text-destructive text-sm">
      {{ formError }}
    </p>

    <Button type="submit" :disabled="isSubmitting">
      <template v-if="isSubmitting">Connexion…</template>
      <template v-else>Se connecter <LucideArrowRight /></template>
    </Button>
  </form>
</template>

<script setup lang="ts">
import { toTypedSchema } from '@vee-validate/zod'
import { useForm } from 'vee-validate'
import { LucideArrowRight, LucideEye, LucideEyeOff, LucideLock, LucideMail } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group'
import { loginSchema } from '~/schemas/login'
import { applyApiErrors } from '~/lib/apiErrors'

definePageMeta({ layout: 'empty', middleware: 'guest' })

const { login } = useAuth()
const showPassword = ref(false)
const formError = ref<string | null>(null)

const form = useForm({
  validationSchema: toTypedSchema(loginSchema),
  initialValues: { email: '', password: '' },
})

const isSubmitting = form.isSubmitting

const onSubmit = form.handleSubmit(async ({ email, password }) => {
  formError.value = null
  const { error } = await login(email, password)

  if (error) {
    formError.value = applyApiErrors(form, error)
    return
  }

  await navigateTo('/')
})
</script>
