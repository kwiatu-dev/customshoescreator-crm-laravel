<template>
  <Sort 
    :sort="sort" 
    :filters="filters" 
    :columns="columns" 
    :order-by="orderBy" 
    :get="get" 
    :page="page" 
    @sort-table="sorting = $event" 
  />
  <table class="w-full">
    <thead>
      <tr>
        <td v-for="(element, field) in columns" :key="field">
          {{ element.label }}
          <button v-if="sortable[field]" class="ml-4" @click="sortTable({field})">
            {{ symbols[sorting[field]] ?? '↓' }}
          </button>
        </td>
        <td>Akcje</td>
      </tr>
    </thead>
    <tbody>
      <tr 
        v-for="object in objects" :key="object.id" 
        :class="{'bg-red-300 dark:bg-red-950': object.deleted_at}"
      >
        <td v-for="(element, field) in columns" :key="field">
          <TableCell :element="element" :field="field" :object="object" />
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
import TableCell from '@/Components/UI/List/TableCell.vue'

const props = defineProps({
  objects: Array,
  filters: Object,
  sort: Object,
  sortable: Object,
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
