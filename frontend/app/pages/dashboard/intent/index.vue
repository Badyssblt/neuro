<template>
<div>
    <div>
        <h3 class="font-serif text-3xl opacity-60">Dites à Neuro <span class="text-primary !opacity-100">ce que vous voulez comprendre.</span> Vous recevez un parcours sur mesure — chapitres, notions, lectures — adapté à votre niveau, en quelques secondes.</h3>
    </div>
    <InputGroup class="border border-foreground/30 rounded-xl">
      <InputGroupTextarea placeholder="Ask, Search or Chat..." class="text-xl"/>
      <InputGroupAddon align="block-end" class="border-t">
        <InputGroupButton
          variant="outline"
          class="rounded-full"
          size="icon-xs"
        >
          <PlusIcon class="size-4" />
        </InputGroupButton>
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <InputGroupButton variant="ghost">
              Auto
            </InputGroupButton>
          </DropdownMenuTrigger>
          <DropdownMenuContent
            side="top"
            align="start"
            class="[--radius:0.95rem]"
          >
            <DropdownMenuItem>Auto</DropdownMenuItem>
            <DropdownMenuItem>Agent</DropdownMenuItem>
            <DropdownMenuItem>Manual</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
        <InputGroupText class="ml-auto">
          52% used
        </InputGroupText>
        <Separator orientation="vertical" class="!h-4" />
        <InputGroupButton
          variant="default"
          class="rounded-full"
          size="icon-xs"
          disabled
        >
          <ArrowUpIcon class="size-4" />
          <span class="sr-only">Send</span>
        </InputGroupButton>
      </InputGroupAddon>
    </InputGroup>
</div>
</template>

<script setup lang="ts">
import { ArrowUpIcon, CheckIcon, InfoIcon, PlusIcon, Search } from 'lucide-vue-next'
import { InputGroup, InputGroupAddon, InputGroupButton, InputGroupInput, InputGroupText, InputGroupTextarea } from '@/components/ui/input-group'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'

</script>