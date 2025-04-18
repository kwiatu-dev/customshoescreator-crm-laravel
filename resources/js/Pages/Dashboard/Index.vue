<template>
  <div v-if="auth?.is_admin" class="flex flex-row justify-between items-center gap-4 mb-4">
    <div />
    <div class="flex flex-row justify-end items-center gap-4">
      <ViewToggle v-model="view" :options="['Użytkownik', 'Ogólne']" />
    </div>
  </div>
  <div v-show="view === 0" class="grid grid-cols-12 gap-2">
    <div class="col-span-12 rounded-md card overflow-visible">
      <div class="label">Wybierz użytkownika: </div>
      <Autocomplete 
        :id="(item) => item.id" 
        v-model:objectId="selectedUserId"
        v-model:searchQuery="userSearchQuery"
        :source="users"
        :fields="['first_name', 'last_name', 'email']"
        :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
        :name="(item) => `${item.first_name} ${item.last_name}`"
      />
    </div>
    <UserStatusSection :user="selectedUser" />
    <UserActualSection :user="selectedUser" />
    <Top3Section />
    <UserChartsSection :user="selectedUser" />
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
import { provide, ref, onMounted, computed, watch } from 'vue'
import { useAuthUser } from '@/Composables/useAuthUser'
import { useProjectYears } from '@/Composables/useProjectYears'
import Autocomplete from '@/Components/UI/Form/Autocomplete.vue'
import { router } from '@inertiajs/vue3'

const view = ref(1)
const auth = useAuthUser()
const selectedUserId = ref(auth.value.id)
const selectedUser = computed(() => props.users.find(user => user.id == selectedUserId.value))
const userSearchQuery = ref(null)
const projectYears = ref(null)

const props = defineProps({
  overallMetrics: {
    type: Object,
    required: false,
  },
  userMetrics: {
    type: Object,
    required: true,
  },
  users: {
    type: Array,
    required: true,
  },
})

onMounted(async () => {
  projectYears.value = await useProjectYears()
})

watch(selectedUserId, () => {
  router.get(
    route('dashboard.index'),
    { user_id: selectedUserId.value },
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
})

provide('project_years', projectYears)
provide('overall_metrics', props.overallMetrics)
provide('user_metrics', props.userMetrics)
console.log()
</script>

