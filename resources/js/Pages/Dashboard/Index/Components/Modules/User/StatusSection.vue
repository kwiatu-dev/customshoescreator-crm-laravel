<template>
  <ModelStatusCards
    class="col-span-12"
    :statuses="{
      awaiting: {
        url: route('projects.index', { status_id: '1', created_by_user: true }),
        count: metrics['total_awaiting_projects_count'],
      },
      in_progress: {
        url: route('projects.index', { status_id: '2', created_by_user: true }),
        count: metrics['total_in_progress_projects_count'],
      },
      after_deadline: {
        url: route('projects.index', { after_deadline: 'true', created_by_user: true }),
        count: metrics['total_after_deadline_projects_count'],
      },
      completed: {
        url: route('projects.index', { status_id: '3', created_by_user: true }),
        count: metrics['total_completed_projects_count'],
      },
    }"
  >
    Projekty
  </ModelStatusCards>
  <ModelStatusCards
    v-if="user?.is_admin"
    class="col-span-12"
    :statuses="{
      awaiting: {
        url: route('investments.index', { status_id: '1', related_with_user_id: user.id }),
        count: metrics['total_active_investments_count'],
      },
      after_deadline: {
        url: route('investments.index', { after_date: 'true', related_with_user_id: user.id }),
        count: metrics['total_after_date_investments_count'],
      },
      completed: {
        url: route('investments.index', { status_id: '2', related_with_user_id: user.id }),
        count: metrics['total_completed_investments_count'],
      },
    }"
  >
    Inwestycje
  </ModelStatusCards>
  <ModelStatusCards
    v-if="user?.is_admin"
    class="col-span-12"
    :statuses="{
      awaiting: {
        url: route('incomes.index', { status_id: '1', related_with_user_id: user.id }),
        count: metrics['total_active_income_count'],
      },
      completed: {
        url: route('incomes.index', { status_id: '2', related_with_user_id: user.id }),
        count: metrics['total_completed_income_count'],
      },
    }"
  >
    Przychód
  </ModelStatusCards>
</template>


<script setup>
import ModelStatusCards from '@/Pages/Dashboard/Index/Components/ModelStatusCards.vue'
import { inject } from 'vue'

defineProps({
  user: {
    type: Object,
    required: true,
  },
})


const metrics = inject('user_metrics')
</script>