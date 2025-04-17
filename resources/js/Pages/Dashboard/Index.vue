<template>
  <div v-if="auth?.is_admin" class="flex flex-row justify-end items-center gap-4 mb-4">
    Widok: 
    <ViewToggle v-model="view" :options="['Użytkownik', 'Ogólne']" />
  </div>

  <div v-show="view === 0" class="grid grid-cols-12 gap-2">
    <UserStatusSection />
    <UserActualSection />
    <Top3Section />
    <UserChartsSection />
  </div>

  <div v-show="view === 1" v-if="auth?.is_admin" class="grid grid-cols-12 gap-2">
    <OverallStatusSection />
    <OverallActualSection />
    <OverallRecordsSection />
    <OverallKPISection />
    <Top3Section />
    <OverallChartsSection />
  </div>
</template>

<script setup>
import UserStatusSection from '@/Pages/Dashboard/Index/Components/Modules/User/StatusSection.vue'
import UserActualSection from '@/Pages/Dashboard/Index/Components/Modules/User/ActualSection.vue'
import UserChartsSection from '@/Pages/Dashboard/Index/Components/Modules/User/ChartsSection.vue'

import OverallStatusSection from '@/Pages/Dashboard/Index/Components/Modules/Overall/StatusSection.vue'
import OverallKPISection from '@/Pages/Dashboard/Index/Components/Modules/Overall/KPISection.vue'
import OverallActualSection from '@/Pages/Dashboard/Index/Components/Modules/Overall/ActualSection.vue'
import OverallRecordsSection from '@/Pages/Dashboard/Index/Components/Modules/Overall/RecordsSection.vue'
import OverallChartsSection from '@/Pages/Dashboard/Index/Components/Modules/Overall/ChartsSection.vue'
import Top3Section from '@/Pages/Dashboard/Index/Components/Modules/Top3Section.vue'
import ViewToggle from '@/Pages/Dashboard/Index/Components/ViewToggle.vue'
import { provide, ref } from 'vue'
import { useAuthUser } from '@/Composables/useAuthUser'

const view = ref(1)
const auth = useAuthUser()

const props = defineProps({
  overallMetrics: {
    type: Object,
    required: false,
  },
  userMetrics: {
    type: Object,
    required: true,
  },
})

provide('overall_metrics', props.overallMetrics)
provide('user_metrics', props.userMetrics)
</script>

