<template>
  <Link :href="route('user.show', {user: object.id})" class="btn-action">Zobacz</Link>
  <Link v-if="!isAdmin && object.editable" :href="route('user.edit', {user: object.id})" class="btn-action">Edytuj</Link>
  <Link v-if="!object.deleted_at && !isAdmin" :href="route('user.destroy', {user: object.id})" method="delete" as="button" class="btn-action" preserve-scroll>Usuń</Link>
  <Link v-if="object.deleted_at" :href="route('user.restore', {user: object.id})" method="put" as="button" class="btn-action" preserve-scroll>Odzyskaj</Link>
  <Link v-if="!isAdmin && object.editable" :href="route('password.email', { email: object.email })" as="button" method="post" class="btn-action" preserve-scroll>
    Resetuj hasło
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  object: Object,
})

const isAdmin = computed(() => props.object?.is_admin == true)

//todo
//1. sprawdzić, czy działa dodawanie (działa), edycja
//2. przy dodawaniu dać możliwość wprowadzania prowizji dla administratorów (zrobione)
//3. ustawić zapamiętywanie stanów
//4. dodać stronę show dla użytkownika (tam dodać linki które pozwalają zadzwonić lub napisać maila, a w projektach kierować na stronę show)
</script>