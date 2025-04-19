<template>
  <ModelStatusCards
    class="col-span-12"
    :statuses="{
      awaiting: {
        url: route('projects.index', { status_id: '1'}),
        count: metrics['total_awaiting_projects_count'],
      },
      in_progress: {
        url: route('projects.index', { status_id: '2'}),
        count: metrics['total_in_progress_projects_count'],
      },
      after_deadline: {
        url: route('projects.index', { after_deadline: 'true'}),
        count: metrics['total_after_deadline_projects_count'],
      },
      completed: {
        url: route('projects.index', { status_id: '3'}),
        count: metrics['total_completed_projects_count'],
      },
    }"
  >
    Projekty
  </ModelStatusCards>
  <ModelStatusCards
    class="col-span-12"
    :statuses="{
      awaiting: {
        url: route('investments.index', { status_id: '1'}),
        count: metrics['total_active_investments_count'],
      },
      after_deadline: {
        url: route('investments.index', { after_date: 'true'}),
        count: metrics['total_after_date_investments_count'],
      },
      completed: {
        url: route('investments.index', { status_id: '2'}),
        count: metrics['total_completed_investments_count'],
      },
    }"
  >
    Inwestycje
  </ModelStatusCards>
  <ModelStatusCards
    class="col-span-12"
    :statuses="{
      awaiting: {
        url: route('incomes.index', { status_id: '1'}),
        count: metrics['total_active_income_count'],
      },
      completed: {
        url: route('incomes.index', { status_id: '2'}),
        count: metrics['total_completed_income_count'],
      },
    }"
  >
    Przychód
  </ModelStatusCards>
</template>


<script setup>
import { inject } from 'vue'
import ModelStatusCards from '@/Pages/Dashboard/Index/Components/ModelStatusCards.vue'

const metrics = inject('overall_metrics')
</script>