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
        <td v-for="([table, column, data], index) in d" :key="table ? `${table}.${column}` : column">
          {{ data.label }}
          <button v-if="sortable[table ? `${table}.${column}` : column]" class="ml-4" @click="sortTable({field: table ? `${table}.${column}` : column})">
            {{ symbols[sorting[table ? `${table}.${column}` : column]] ?? '↓' }}
          </button>
        </td>
        <td class="sticky-column">Akcje</td>
      </tr>
    </thead>
    <tbody>
      <tr 
        v-for="object in objects" :key="object.id" 
        :style="styles?.row ? styles.row(object) : null"
      >
        <td 
          v-for="([table, column, data], index) in d" 
          :key="table ? `${table}.${column}` : column"
          :class="{'line-through': object.deleted_at}"
        >
          <TableCell :element="data" :field="column" :object="table ? object[table] : object" />
        </td>
        <td class="sticky-column">
          <div class="flex flex-col items-start">
            <component :is="actions" :object="object" />
          </div>
        </td>
      </tr>
    </tbody>
    <tfoot v-if="footer">
      <tr>
        <td v-for="([table, column, data], index) in d" :key="table ? `${table}.${column}` : column" class="font-bold">
          {{ footer[table ? `${table}.${column}` : column] ? footer[table ? `${table}.${column}` : column] + ' ' + data.suffix: '-' }}
        </td>
      </tr>
    </tfoot>
  </table>
</template>

<script setup>
import { ref } from 'vue'
import Sort from '@/Components/UI/List/Sort.vue'
import TableCell from '@/Components/UI/List/TableCell.vue'
import { useListColumns } from '@/Composables/useListColumns'

const props = defineProps({
  styles: Object,
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

const d = useListColumns(props.columns)
</script>

<style scoped>
.sticky-column {
  position: sticky;
  right: 0;
  z-index: 10;

  @apply bg-indigo-900;
}

table {
  border-collapse: separate;
  border-spacing: 0px;
  border: none;
  border-top: 1px solid #ccc; 
}

td, th {
  border: none;
  border-bottom: 1px solid #ccc;
  border-right: 1px solid #ccc;
}

td:first-child, th:first-child {
  border-left: 1px solid #ccc;
}
</style>
