<template>
  {{ client.street }} {{ (client.street_nr && client.apartment_nr ? client.street_nr + '/' + client.apartment_nr : client.street_nr || client.apartment_nr) }}
</template>

<script setup>
defineProps({
  client: Object,
})
</script>