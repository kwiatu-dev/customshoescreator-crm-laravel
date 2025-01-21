<template>
  <div class="bg-gray-700 p-4 rounded-sm">
    <div v-if="income.project_id" class="grid grid-cols-1 gap-2">
      <div>
        <span>Projekt: </span>
        <Link :href="route('projects.show', { project: ''}) + '/' + income.project_id" preserve-state>
          <span class="font-medium underline">Zobacz</span>
        </Link>
      </div>
      <div class="line m-0" />
      <div>
        <span>Wykonawca: </span>
        <Link :href="route('user.show', { user: '' }) + '/' + income.project.created_by_user_id" preserve-state>
          <span class="font-medium underline">{{ name(income.project.created_by_user_id, users) }}</span>
        </Link>
      </div>
      <div class="line m-0" />
      <div>
        <span>Tytuł: </span>
        <span class="font-medium">{{ income.title }}</span>
      </div>
      <div class="line m-0" />
      <div>
        <span>Data: </span>
        <span class="font-medium">{{ dayjs().format('YYYY-MM-DD') }}</span>
      </div>
      <div class="line m-0" />
      <div>
        <span>Kwota: </span>
        <span class="font-medium">{{ income.price }} zł</span>
      </div>
      <div class="line m-0" />
      <div>
        <span>Koszty stałe: </span>
        <span class="font-medium">{{ income.project.costs }}% to {{ projectCosts.organizationProfit }} zł</span>
      </div>
      <div class="line m-0" />
      <div>
        <span>Prowizja: </span>
        <span class="font-medium">{{ income.project.commission }}% to {{ projectCosts.employeeProfit }} zł</span>
      </div>
      <div class="line m-0" />
      <div v-if="projectDistribution">
        <div v-for="[userId, percentage] in Object.entries(projectDistribution)" :key="userId">
          <div>
            <span>{{ name(userId, users) + ': ' }}</span>
            <span class="font-medium">{{ percentage }}% to {{ projectCosts.managementDistribution.value[userId] }} zł</span>
          </div>
          <div class="line m-0 my-2" />
        </div>
      </div>
    </div>

    <div v-if="!income.project_id" class="grid grid-cols-1 gap-2 mt-2">
      <div>
        <span>Tytuł: </span>
        <span class="font-medium">{{ income.title }}</span>
      </div>
      <div class="line m-0" />
      <div>
        <span>Data: </span>
        <span class="font-medium">{{ dayjs().format('YYYY-MM-DD') }}</span>
      </div>
      <div class="line m-0" />
      <div>
        <span>Kwota: </span>
        <span class="font-medium">{{ income.price }} zł</span>
      </div>
      <div class="line m-0" />
      <div>
        <span>Koszty stałe: </span>
        <span class="font-medium">{{ income.costs }}% to {{ incomeCosts.organizationProfit }} zł</span>
      </div>
      <div class="line m-0" />
      <div v-if="incomeDistribution">
        <div v-for="[userId, percentage] in Object.entries(incomeDistribution)" :key="userId">
          <div>
            <span>{{ name(userId, users) + ': ' }}</span>
            <span class="font-medium">{{ percentage }}% to {{ incomeCosts.usersDistribution.value[userId] }} zł</span>
          </div>
          <div class="line m-0 my-2" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useProjectCosts } from '@/Composables/useProjectCosts'
import { useIncomeCosts } from '@/Composables/useIncomeCosts'
import { Link } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { inject, ref } from 'vue'

const props = defineProps({
  income: {
    type: Object,
    required: true,
  },
})

const incomeDistribution = ref(typeof props.income.distribution === 'string' ? JSON.parse(props.income.distribution) : props.income.distribution)
const incomeCosts = useIncomeCosts(props.income)
const projectDistribution = ref(typeof props.income?.project?.distribution === 'string' ? JSON.parse(props.income.project.distribution) : props.income?.project?.distribution)
const projectCosts = props.income?.project ? useProjectCosts(props.income.project) : ref(null)

const name = (userId, users) => {
  const u = users.find(user => user.id == userId)

  return `${u?.first_name} ${u?.last_name}`
}

const users = inject('users')
</script>