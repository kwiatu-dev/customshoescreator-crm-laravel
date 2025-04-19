<template>
  <div v-if="auth?.is_admin" class="flex flex-row justify-between items-center gap-4 mb-4">
    <div />
    <div class="flex flex-row justify-end items-center gap-4">
      <ViewToggle v-model="view" :options="['Użytkownik', 'Ogólne']" />
    </div>
  </div>
  <div v-show="parseInt(view) === 0" class="grid grid-cols-12 gap-2">
    <div v-if="auth?.is_admin" class="col-span-12 rounded-md card overflow-visible">
      <div class="label">Dane użytkownika: </div>
      <Autocomplete 
        :id="(item) => item.id" 
        v-model:objectId="selectedUserId"
        v-model:searchQuery="userSearchQuery"
        :source="users"
        :fields="['first_name', 'last_name', 'email']"
        :list="(item) => `${item.first_name} ${item.last_name}: ${item.email}`"
        :name="(item) => `${item.first_name} ${item.last_name} #${item.id}`"
      />
    </div>
    <UserStatusSection :user="selectedUser" />
    <UserActualSection :user="selectedUser" />
    <Top3Section />
    <UserChartsSection :user="selectedUser" />
  </div>
  <div v-show="parseInt(view) === 1" v-if="auth?.is_admin" class="grid grid-cols-12 gap-2">
    <OverallStatusSection />
    <OverallActualSection />
    <OverallRecordsSection />
    <OverallKPISection />
    <Top3Section />
    <OverallChartsSection />
  </div>
</template>

<script setup>
import UserStatusSection from '@/Pages/Dashboard/Index/Components/Modules/User/UserStatusSection.vue'
import UserActualSection from '@/Pages/Dashboard/Index/Components/Modules/User/UserActualSection.vue'
import UserChartsSection from '@/Pages/Dashboard/Index/Components/Modules/User/UserChartsSection.vue'
import OverallStatusSection from '@/Pages/Dashboard/Index/Components/Modules/Overall/OverallStatusSection.vue'
import OverallKPISection from '@/Pages/Dashboard/Index/Components/Modules/Overall/OverallKPISection.vue'
import OverallActualSection from '@/Pages/Dashboard/Index/Components/Modules/Overall/OverallActualSection.vue'
import OverallRecordsSection from '@/Pages/Dashboard/Index/Components/Modules/Overall/OverallRecordsSection.vue'
import OverallChartsSection from '@/Pages/Dashboard/Index/Components/Modules/Overall/OverallChartsSection.vue'
import Top3Section from '@/Pages/Dashboard/Index/Components/Modules/Top3Section.vue'
import ViewToggle from '@/Components/UI/Others/ViewToggle.vue'
import Autocomplete from '@/Components/UI/Form/Autocomplete.vue'
import { provide, ref, computed, watch } from 'vue'
import { useAuthUser } from '@/Composables/useAuthUser'
import { router } from '@inertiajs/vue3'
import { useQueryParams } from '@/Composables/useQueryParams'

const auth = useAuthUser()
const queryParams = computed(() => useQueryParams())
const view = auth.value?.is_admin ? ref(queryParams.value['view'] ?? 1) : 0
const selectedUserId = auth.value?.is_admin ? ref(queryParams.value['user_id'] ?? auth.value.id) : auth.value.id
const selectedUser = auth.value?.is_admin ? computed(() => props.users.find(user => user.id == selectedUserId.value)) : auth.value
const userSearchQuery = ref(null)


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
    type: [Array, null],
    required: true,
  },
})

if (auth.value?.is_admin) {
  watch(selectedUserId, () => {
    queryParams.value['user_id'] = selectedUserId.value

    router.get(
      route('dashboard.index'),
      queryParams.value,
      {
        preserveState: false,
        preserveScroll: true,
      },
    )
  })

  watch(view, () => {
    queryParams.value['view'] = view.value

    router.get(
      route('dashboard.index'),
      queryParams.value,
      {
        preserveState: true,
        preserveScroll: true,
      },
    )
  })
}



provide('overall_metrics', props.overallMetrics)
provide('user_metrics', props.userMetrics)
</script>

