<template>
  <Box v-for="client in clients" :key="client.id" class="flex flex-col gap-2 px-6">
    <div v-if="client.deleted_at" class="text-xs font-bold uppercase border border-dashed p-1 border-red-300 text-red-500 dark:border-red-600 dark:text-red-600 inline-block rounded-md mb-2 self-start">USUNIĘTE</div>
    <div class="flex flex-row flex-nowrap gap-8">
      <div class="flex-1">
        <div class="text-xl mb-2">
          {{ client.first_name }} {{ client.last_name }}
        </div>
        <div class="text-gray-400">
          <a :href="client.social_link" class="text-indigo-600 hover:text-indigo-500">
            {{ client.username ? '@' + client.username : '' }}
          </a>
        </div>
        <div class="text-gray-400">{{ client.email }}</div>
        <div class="text-gray-400">{{ client.phone }}</div>
      </div>
      <div class="flex-1">
        <div class="invisible mb-2">blank</div>
        <div class="text-gray-400">
          <ClientAddress :client="client" />
        </div>
        <div class="text-gray-400">{{ client.postcode }}, {{ client.city }}</div>
        <div class="text-gray-400">{{ client.country }}</div>
      </div>
    </div>
    <div class="flex flex-row flex-nowrap gap-2 mb-1 mt-4">
      <Link href="#" class="btn-outline">Edytuj</Link>
      <Link v-if="!client.deleted_at" :href="route('client.destroy', {client: client.id})" method="delete" as="button" class="btn-outline">Usuń</Link>
      <Link v-if="client.deleted_at" :href="route('client.restore', {client: client.id})" method="put" as="button" class="btn-outline">Odzyskaj</Link>
    </div>
  </Box>
</template>

<script setup>
import ClientAddress from '@/Pages/Client/Index/Components/ClientAddress.vue'
import Box from '@/Components/UI/Box.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  clients: Array,
})
</script>