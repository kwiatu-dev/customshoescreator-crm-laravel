<template>
  <div>
    <div v-for="user in includedUsers" :key="user.id" class="flex flex-row flex-nowrap justify-between items-center w-full">
      <div>
        {{ user.first_name }} {{ user.last_name }}
      </div>
      <div class="line" />
      <div>{{ distribution[user.id] }}%</div>
    </div>
  </div>
</template>
  
<script setup>
import { inject, ref } from 'vue'
  
const props = defineProps({
  object: { type: Object, required: true },
})
  
const distribution = typeof props.object.distribution === 'string' ? JSON.parse(props.object.distribution) : props.object.distribution
const users = inject('users')
const includedUsers = ref(null)

if (users && distribution) {
  includedUsers.value = users.filter(user => Object.keys(distribution).includes(user.id.toString()))
}
</script>
  
  <style scope>
  .line {
    flex-grow: 1; 
    height: 1px; 
    @apply border-b border-dotted border-gray-400 mx-4;
  }
</style>