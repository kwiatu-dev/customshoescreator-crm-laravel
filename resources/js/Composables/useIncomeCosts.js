import { computed } from 'vue'

export const useIncomeCosts = (income) => { 
  const price = parseFloat(income.price)
  const costs = parseInt(income.costs)
  const distribution = typeof income.distribution === 'string' ? JSON.parse(income.distribution) : income.distribution
  
  const organizationProfit = computed(() => parseFloat((price * costs / 100).toFixed(2)))
  const restForDistribution = computed(() => price - organizationProfit.value)

  if (!distribution) {
    return { organizationProfit, restForDistribution, usersDistribution: null }
  }

  const usersDistribution = computed(() => {
    return Object.entries(distribution).reduce((acc, [userId, userPercentage]) => {
      acc[userId] = parseFloat((restForDistribution.value * userPercentage / 100).toFixed(2))
      return acc
    }, {})
  })

  return { organizationProfit, restForDistribution, usersDistribution }
}
  

  