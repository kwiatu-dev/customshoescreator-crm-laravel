import { computed } from 'vue'

export const useListColumns = (columns) => { 
  const withMultipleColumns = computed(() =>
    Object.entries(columns)
      .filter(([_, element]) => typeof element.columns === 'object' && !Array.isArray(element.columns))
      .flatMap(([table, element]) =>
        Object.entries(element.columns).map(([column, data]) => [table, column, data]),
      ),
  )

  const withMultipleDimensions = computed(() => 
    Object.entries(columns)
      .filter(([field, _]) => field.includes('.'))
      .map(([field, data]) => {
        const s = field.split('.')
        
        return [s[0], s[1], data]
      }),
  )
      
  const withoutMultipleColumns = computed(() => 
    Object.entries(columns)
      .filter(([field, _]) => !field.includes('.'))
      .filter(([field, element], index) => {
        return element.columns === undefined || Array.isArray(element.columns)
      })
      .map(([column, data]) => [null, column, data]))

  return computed(() => 
    [...withMultipleColumns.value, ...withoutMultipleColumns.value, ...withMultipleDimensions.value]
      .sort(([table1, column1, data1], [table2, column2, data2]) => {
        const orderA = data1.order ?? Infinity 
        const orderB = data2.order ?? Infinity 
        return orderA - orderB 
      }),
  )
}