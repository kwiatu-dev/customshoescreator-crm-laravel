<template>
  <!-- <div v-if="sortOrder.length">
    Sortowanie: 
    <draggable 
      v-model="sortOrder" 
      tag="div" 
      group="sort" 
      item-key="column" 
      class="flex flex-row flex-nowrap gap-2 mb-2 mt-1"
      @end="sort"
    >
      <template #item="{ element, index }">
        <div class="py-2 px-4 text-sm border rounded-md border-gray-300 cursor-grab bg-gray-800">
          <span>{{ element }}</span>
          <span class="ml-3 font-bold cursor-pointer p-1" @click="remove(element, index)">x</span>
        </div>
      </template>
    </draggable>
  </div> -->
  <div />
</template>

<script setup>
// import { router } from '@inertiajs/vue3'
//import { reactive, watch, ref } from 'vue'
// import { debounce } from 'lodash'
// import draggable from 'vuedraggable'

const props = defineProps({
  labels: Object,
  sort: Object, 
  filters: Object,
  orderBy: String,
  page: Number,
})




// const sortOrder = ref(
//   Object.keys(props.sort ?? null),
// )


// const sortForm = reactive({
//   ...props.sort ?? null,
// })

// const sortBy = (column) => {
//   sortForm[column] = sortForm[column] === 'desc' ? 'asc' : 'desc'
// }

// const getSortSymbol = (column) => {
//   return sortSymbols[sortForm[column]] ?? '↓'
// }

// const sortSymbols = {
//   asc: '↓',
//   desc: '↑',
// }


// const getSortFieldsWithOrder = () => {
//   if(sortOrder.value.length <= 1)
//     return sortForm

//   const final = {}

//   for (const column of sortOrder.value) {
//     if(sortForm[column] ?? false){
//       final[column] = sortForm[column]
//     }
//   }

//   return final
// }

// const remove = (column) => {
//   delete sortForm[column]
// }

// const sort = () => {
//   const query = {...getSortFieldsWithOrder(), ...props.filters}

//   if(props.page > 1) 
//     query['page'] = props.page

//   router.get(
//     route('user.index'),
//     query,
//     {
//       preserveState: true,
//       preserveScroll: true,
//       onSuccess: () => {
//         sortOrder.value = Object.keys(props.sort)
//       },
//     },
//   )
// }

// watch(sortForm, debounce(() => sort(), 500))
</script>