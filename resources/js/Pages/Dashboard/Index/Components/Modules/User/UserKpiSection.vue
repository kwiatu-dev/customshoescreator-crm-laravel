<template>
  <SectionTitle ref="el" class="col-span-12">
    <div>Podsumowanie KPI</div>
    <DateRangePicker v-model="range" class="ml-auto mt-4 md:ml-0 md:mt-0" />
  </SectionTitle>
  <IconStatsCard 
    class="col-span-12 md:col-span-4"
    :icon="['fas', 'sack-dollar']"
    :data="kpi?.['financial'] || null"
    :labels="{ income: 'Przychód', earnings: 'Zarobek' }"
    :units="{ income: ' zł', earnings: ' zł' }"
    :only-for-admin="{ income: true }"
  >
    Finanse
  </IconStatsCard>
  <IconStatsCard 
    class="col-span-12 md:col-span-4"
    :icon="['fas', 'sack-dollar']"
    :data="kpi?.['projects'] || null"
    :labels="{ new: 'Nowe', completed: 'Zakończone', avg_days: 'Średni czas realizacji', }"
    :units="{ avg_days: ' dni' }"
  >
    Projekty
  </IconStatsCard>
  <IconStatsCard 
    class="col-span-12 md:col-span-4"
    :icon="['fas', 'people-group']"
    :data="kpi?.['clients'] || null"
    :labels="{ new: 'Nowi' }"
    :units="{}"
  >
    Klienci
  </IconStatsCard>
  <StatsCard 
    class="col-span-6 sm:col-span-4 md:col-span-2"
    :data="kpi?.['projects']?.['types']?.['renowacja-butow'] || null"
  >
    Renowacja butów
  </StatsCard>
  <StatsCard 
    class="col-span-6 sm:col-span-4 md:col-span-2"
    :data="kpi?.['projects']?.['types']?.['personalizacja-butow'] || null"
  >
    Personalizacja butów
  </StatsCard>
  <StatsCard 
    class="col-span-6 sm:col-span-4 md:col-span-2"
    :data="kpi?.['projects']?.['types']?.['personalizacja-ubran'] || null"
  >
    Personalizacja ubrań
  </StatsCard>
  <StatsCard 
    class="col-span-6 sm:col-span-4 md:col-span-2"
    :data="kpi?.['projects']?.['types']?.['haft-reczny'] || null"
  >
    Haft ręczny
  </StatsCard>
  <StatsCard 
    class="col-span-6 sm:col-span-4 md:col-span-2"
    :data="kpi?.['projects']?.['types']?.['haft-komputerowy'] || null"
  >
    Haft komputerowy
  </StatsCard>
  <StatsCard 
    class="col-span-6 sm:col-span-4 md:col-span-2"
    :data="kpi?.['projects']?.['types']?.['inne'] || null"
  >
    Inne
  </StatsCard>
</template>
  
<script setup>
import dayjs from 'dayjs'
import { nextTick, ref, watch } from 'vue'
import debounce  from 'lodash/debounce'
import IconStatsCard from '@/Pages/Dashboard/Index/Components/IconStatsCard.vue'
import StatsCard from '@/Pages/Dashboard/Index/Components/StatsCard.vue'
import DateRangePicker from '@/Components/UI/Others/DateRangePicker.vue'
import SectionTitle from '@/Pages/Dashboard/Index/Components/SectionTitle.vue'
import { getKpi } from '@/Helpers/stats'
import { useElementInViewPortVisibility } from '@/Composables/useElementInViewPortVisibility'

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
})
  
const range = ref({ from: dayjs().format('YYYY-MM'), to: dayjs().format('YYYY-MM') })
const kpi = ref(null)
const el = ref(null)
  
const fetch = debounce(async () => {
  if (range.value) {
    await nextTick()
    kpi.value = await getKpi(range.value, props.user.id)
  }
}, 1000)
  
watch(() => range.value, () => {
  kpi.value = null
  fetch()
}, { deep: true })
  
useElementInViewPortVisibility(el, async () => {
  if (kpi.value === null)
    kpi.value = await getKpi(range.value, props.user.id)
})
</script>