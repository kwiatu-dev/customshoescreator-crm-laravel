<template>
  <div class="mb-4">
    <Link :href="route('realtor.listing.index')">Go back to Listings</Link>
  </div>

  <section class="flex flex-col-reverse md:grid- md:grid-cols-12 gap-4">
    <Box class="flex md:col-span-7 items-center">
      <div class="w-full text-center font-medium text-gray-500">
        No offers
      </div>
    </Box>
    <Box class="md:col-span-5">
      <template #header>Basic info</template>
      <Price :price="listing.price" class="text-2xl font-bold" />
      <ListingSpace :listing="listing" class="text-lg" />
      <ListingAddress :listing="listing" class="text-gray-500" />
    </Box>
  </section>
</template>

<script setup>
import ListingAddress from '@/Components/ListingAddress.vue'
import ListingSpace from '@/Components/ListingSpace.vue'
import Price from '@/Components/Price.vue'
import Box from '@/Components/UI/Box.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  listing: Object,
})
</script>