<template>
  <div class="grid grid-cols-12 gap-2">
    <StatusSection />
    <ActualSection />
    <RecordsSection />
    <KPISection />
    <Top3Section />
    <ChartsSection />
  </div>
</template>

<script setup>
import StatusSection from '@/Pages/Dashboard/Index/Components/Modules/StatusSection.vue'
import KPISection from '@/Pages/Dashboard/Index/Components/Modules/KPISection.vue'
import ActualSection from '@/Pages/Dashboard/Index/Components/Modules/ActualSection.vue'
import RecordsSection from '@/Pages/Dashboard/Index/Components/Modules/RecordsSection.vue'
import ChartsSection from '@/Pages/Dashboard/Index/Components/Modules/ChartsSection.vue'
import Top3Section from '@/Pages/Dashboard/Index/Components/Modules/Top3Section.vue'
import { provide } from 'vue'

const props = defineProps({
  metrics: {
    type: Object,
    required: true,
  },
})

provide('metrics', props.metrics)
</script>

