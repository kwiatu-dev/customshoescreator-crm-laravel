<template>
  <div v-if="auth?.is_admin" class="flex flex-row justify-end items-center gap-4 mb-4">
    Widok: 
    <ViewToggle v-model="view" :options="['Użytkownik', 'Admin']" />
  </div>

  <div v-show="view === 0" v-if="auth?.is_admin" class="grid grid-cols-12 gap-2">
    test
  </div>

  <div v-show="view === 1" v-if="auth?.is_admin" class="grid grid-cols-12 gap-2">
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
import ViewToggle from '@/Pages/Dashboard/Index/Components/ViewToggle.vue'
import { provide, ref } from 'vue'
import { useAuthUser } from '@/Composables/useAuthUser'

const view = ref(1)
const auth = useAuthUser()

const props = defineProps({
  metrics: {
    type: Object,
    required: true,
  },
})

provide('metrics', props.metrics)
</script>

