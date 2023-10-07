<template>
  <Sort :sort="sort" :filters="filters" :columns="columns" :order-by="orderBy" :get="get" :page="page" @sort-table="sorting = $event" />
  <table>
    <thead>
      <tr>
        <td v-for="(element, field) in columns" :key="field">
          {{ element.label }}
          <button class="ml-4" @click="sortTable({field})">
            {{ symbols[sorting[field]] ?? '↓' }}
          </button>
        </td>
        <td>Akcje</td>
      </tr>
    </thead>
    <tbody>
      <tr v-for="object in objects" :key="object.id" :class="{'bg-red-300 dark:bg-red-950': object.deleted_at}">
        <td v-for="(element, field) in columns" :key="field">
          <a v-if="element.link" :href="(element.prefix ?? '') + object[element.link] + (element.suffix ?? '')" target="_blank" class="text-indigo-600 hover:text-indigo-500">
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
  columns: Object,
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
