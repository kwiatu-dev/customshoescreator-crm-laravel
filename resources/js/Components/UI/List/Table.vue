<template>
  <Sort :sort="sort" :filters="filters" :columns="columns" :order-by="orderBy" :get="get" :page="page" @sort-table="sorting = $event" />
  <table class="w-full">
    <thead>
      <tr>
        <td v-for="(element, field) in columns" :key="field">
          {{ element.label }}
          <button v-if="element.sortable" class="ml-4" @click="sortTable({field})">
            {{ symbols[sorting[field]] ?? '↓' }}
          </button>
        </td>
        <td>Akcje</td>
      </tr>
    </thead>
    <tbody>
      <tr v-for="object in objects" :key="object.id" :class="{'bg-red-300 dark:bg-red-950': object.deleted_at}">
        <td v-for="(element, field) in columns" :key="field">
          <a v-if="element.link" :href="(element.link.prefix ?? '') + (object[field][element.link.field] ?? object[element.link.field]) + (element.link.suffix ?? '')" target="_blank" class="text-indigo-600 hover:text-indigo-500">
            {{ element?.columns ? element.columns.map(key => object[field][key]).join(' ') : object[field] }} {{ element.suffix }}
          </a>
          <span v-else>{{ element?.columns ? element.columns.map(key => object[field][key]).join(' ') : object[field] }} {{ element.suffix }}</span>
        </td>
        <td>
          <div class="flex flex-col items-start">
            <component :is="actions" :object="object" />
          </div>
        </td>
      </tr>
    </tbody>
    <tfoot v-if="footer">
      <tr>
        <td v-for="(element, field) in columns" :key="field" class="font-bold">
          {{ footer[field] ? footer[field] + ' ' + element.suffix: '-' }}
        </td>
      </tr>
    </tfoot>
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
  footer: Object,
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
