<template>
  <SectionTitle class="col-span-12">
    Aktualne
  </SectionTitle>
  <IconCard 
    class="col-span-12" 
    :icon="['fas', 'wallet']"
    :value="metrics['wallet'] + ' zł'"
  >
    Stan konta
  </IconCard>
  <IconCard 
    class="col-span-12 md:col-span-6 " 
    :icon="['far', 'hourglass-half']"
    :value="metrics['total_awaiting_income_sum'] + ' zł'"
  >
    Oczekujące na realizację
  </IconCard>
  <IconCard 
    class="col-span-12 md:col-span-6 " 
    :icon="['fas', 'money-bill-transfer']"
    :value="metrics['total_awaiting_repayment_sum'] + ' zł'"
  >
    Do zwrotu
  </IconCard>
</template>

<script setup>
import SectionTitle from '@/Pages/Dashboard/Index/Components/SectionTitle.vue'
import IconCard from '@/Pages/Dashboard/Index/Components/IconCard.vue'
import { inject } from 'vue'

const metrics = inject('overall_metrics')
</script>