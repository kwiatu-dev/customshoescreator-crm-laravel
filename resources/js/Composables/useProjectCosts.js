import { computed } from 'vue'

export const useProjectCosts = (project) => {
  const visualization = parseFloat(project.visualization)
  const price = parseFloat(project.price)
  const costs = parseFloat(project.costs)
  const commission = parseFloat(project.commission)
  const distribution = typeof project.distribution === 'string' ? JSON.parse(project.distribution) : project.distribution

  const organizationProfit = computed(() => parseFloat((price * costs / 100).toFixed(2)))
  const employeeProfit = computed(() => parseFloat((((price - organizationProfit.value) * commission / 100) + visualization).toFixed(2)))
  const managementProfit = computed(() => parseFloat((price - organizationProfit.value - employeeProfit.value + visualization).toFixed(2)))

  const managementDistribution = computed(() => Object.keys(distribution).reduce((acc, key) => {
    acc[key] = parseFloat((distribution[key] * managementProfit.value / 100).toFixed(2))
    return acc
  }, {}))

  return { organizationProfit, employeeProfit, managementProfit, managementDistribution }
}