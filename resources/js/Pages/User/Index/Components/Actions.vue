<template>
  <RememberStateButton v-if="!disableShowButton" label="Zobacz" :url="route('user.show', { user: object.id })" />
  <RememberStateButton v-if="!isAdmin && object.editable" label="Edytuj" :url="route('user.edit', { user: object.id })" />
  <Link v-if="!object.deleted_at && !isAdmin" :href="route('user.destroy', {user: object.id})" method="delete" as="button" class="btn-action" preserve-scroll>Usuń</Link>
  <Link v-if="object.deleted_at" :href="route('user.restore', {user: object.id})" method="put" as="button" class="btn-action" preserve-scroll>Odzyskaj</Link>
  <Link v-if="!isAdmin && object.editable" :href="route('password.email', { email: object.email })" as="button" method="post" class="btn-action" preserve-scroll>
    Resetuj hasło
  </Link>
</template>

<script setup>
import RememberStateButton from '@/Components/UI/Buttons/RememberStateButton.vue'
import { Link } from '@inertiajs/vue3'
import { computed, inject } from 'vue'

const props = defineProps({
  object: Object,
})

const isAdmin = computed(() => props.object?.is_admin == true)
const disableShowButton = inject('disable_show_button', false)
</script>