<template>
  <ListLayout 
    :objects="users" 
    :filters="filters" 
    :sort="sort" 
    :labels="labels" 
    :get="'user.index'"
    :actions="actions"
  >
    <template #title>
      Lista użytkowników
    </template>
    <template #create>
      <Link
        :href="route('user.create')" 
        class="btn-primary w-1/3 rounded-l-none h-11 text-center md:rounded-l-md md:w-auto md:text-left"
      >
        + Dodaj <span class="hidden md:inline">użytkownika</span>
      </Link>
    </template>
  </ListLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import ListLayout from '@/Components/UI/List/Layout.vue'
import actions from '@/Pages/User/Index/Components/Actions.vue'

defineProps({
  users: Object,
  filters: Object,
  sort: Object,
  labels: Object,
})
</script>