<template>
  <div>
    <div v-for="admin in admins" :key="admin.id" class="flex flex-row flex-nowrap justify-between items-center w-full">
      <div>
        {{ admin.first_name }} {{ admin.last_name }}
      </div>
      <div class="line" />
      <div>{{ distribution[admin.id] }}%</div>
    </div>
  </div>
</template>

<script setup>
import { inject } from 'vue'

const props = defineProps({
  object: { type: Object, required: true },
})

const distribution = JSON.parse(props.object.distribution)
const users = inject('users')
let admins = null

if (users) {
  admins = inject('users').filter(user => user.is_admin === 1)
}
</script>

<style scope>
.line {
  flex-grow: 1; 
  height: 1px; 
  @apply border-b border-dotted border-gray-400 mx-4;
}
</style>