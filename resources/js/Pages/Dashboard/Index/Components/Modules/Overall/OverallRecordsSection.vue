<template>
  <SectionTitle class="col-span-12">
    Pozycje
  </SectionTitle>
  <ValueCard 
    class="col-span-6 md:col-span-4"
    :value="metrics['total_users_count']"
  >
    Użytkownicy
  </ValueCard>
  <ValueCard 
    class="col-span-6 md:col-span-4"
    :value="metrics['total_clients_count']"
  >
    Klienci
  </ValueCard>
  <ValueCard 
    class="col-span-6 md:col-span-4"
    :value="metrics['total_projects_count']"
  >
    Projekty
  </ValueCard>
  <ValueCard 
    class="col-span-6 md:col-span-4"
    :value="metrics['total_investments_count']"
  >
    Inwestycje
  </ValueCard>
  <ValueCard 
    class="col-span-6 md:col-span-4"
    :value="metrics['total_income_count']"
  >
    Przychód
  </ValueCard>
  <ValueCard 
    class="col-span-6 md:col-span-4"
    :value="metrics['total_expenses_count']"
  >
    Wydatki
  </ValueCard>
</template>

<script setup>
import SectionTitle from '@/Pages/Dashboard/Index/Components/SectionTitle.vue'
import ValueCard from '@/Pages/Dashboard/Index/Components/ValueCard.vue'
import { inject } from 'vue'

const metrics = inject('overall_metrics')
</script>