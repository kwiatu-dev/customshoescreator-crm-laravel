<template>
  <Sort :sort="sort" :filters="filters" :labels="labels" :order-by="orderBy" :get="get" :page="page" @sort-table="sorting = $event" />
  <table>
    <thead>
      <tr>
        <td v-for="(element, field) in labels" :key="field">
          {{ element.label }}
          <button class="ml-4" @click="sortTable({field})">
            {{ symbols[sorting[field]] ?? '↓' }}
          </button>
        </td>
        <td>Akcje</td>
      </tr>
    </thead>
    <tbody>
      <tr v-for="object in objects" :key="object.id">
        <td v-for="(element, field) in labels" :key="field">
          <a v-if="element.link" :href="object[element.link]" target="_blank" class="text-indigo-600 hover:text-indigo-500">
            {{ object[field] }}
          </a>
          <span v-else>{{ object[field] }}</span>
        </td>
        <td>
          <div class="flex flex-col items-start">
            <component :is="actions" :object="object" />
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
import { ref } from 'vue'
import Sort from '@/Components/UI/List/Sort.vue'

const props = defineProps({
  objects: Array,
  filters: Object,
  sort: Object,
  labels: Object,
  get: String, 
  page: Number,
  actions: Object,
})

const symbols = {
  asc: '↓',
  desc: '↑',
}

const sorting = ref({...props.sort ?? null})
const orderBy = ref({})
const sortTable = (field) => orderBy.value = field
</script>
