<template>
  <SectionTitle class="col-span-12">
    Aktualne
  </SectionTitle>
  <IconCard 
    class="col-span-12 md:col-span-6 " 
    :icon="['far', 'hourglass-half']"
    :value="metrics['total_user_awaiting_income_sum'] + ' zł'"
  >
    Oczekujące
  </IconCard>
  <IconCard 
    class="col-span-12 md:col-span-6 " 
    :icon="['fas', 'money-bill-transfer']"
    :value="metrics['total_investor_awaiting_repayment_sum'] + ' zł'"
  >
    Do zwrotu
  </IconCard>
</template>
  
<script setup>
import SectionTitle from '@/Pages/Dashboard/Index/Components/SectionTitle.vue'
import IconCard from '@/Pages/Dashboard/Index/Components/IconCard.vue'
import { inject } from 'vue'
  
const metrics = inject('user_metrics')
</script>