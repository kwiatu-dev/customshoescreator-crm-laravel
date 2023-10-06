<template>
  <Sort :sort="sort" :filters="filters" :labels="labels" :order-by="orderBy" />
  <table>
    <thead>
      <tr>
        <td v-for="(label, field) in labels" :key="field">
          {{ label }}
          <button class="ml-4" @click="sortTable(field)">
            V
          </button>
        </td>
      </tr>
    </thead>
    <tbody>
      <tr v-for="object in objects" :key="object.id">
        <td v-for="(label, field) in labels" :key="field">
          {{ object[field] }}
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
import { ref } from 'vue'
import Sort from '@/Components/UI/Table/Sort.vue'

const props = defineProps({
  objects: Array,
  filters: Object,
  sort: Object,
  labels: Object,
})

const orderBy = ref('')
const sortTable = (field) => orderBy.value = field


</script>
