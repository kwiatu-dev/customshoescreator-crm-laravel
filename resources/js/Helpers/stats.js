import api from '@/Helpers/api'

const monthLabels = ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień']
const projectTypeLabels = ['Renowacja butów', 'Personalizacja butów', 'Personalizacja ubrań', 'Haft ręczny', 'Haft komputerowy', 'Inne'] 

export const getMonthlyFinancialStatsChartData = async (user_id) => {
  const years = api.get('dashboard.income-years', { user_id })
  const data = {}
  
  for (const year of await years) {
    data[year] = await api.get('dashboard.monthly-financial-stats', { year, user_id })
  }

  console.log(data)
  
  return data
}

export const getMonthlyCompletedProjectsCountChartData = async (user_id) => {
  const years = api.get('dashboard.project-years', { user_id })

  const data = {
    labels: monthLabels,
    datasets: [],
  }
  
  for (const year of await years) {
    data.datasets.push({
      label: year,
      data: (await api.get('dashboard.monthly-completed-projects-count', { year, user_id })).data,
    })
  }
  
  return data
}

export const getMonthlyNewProjectsCountChartData = async (user_id) => {
  const years = api.get('dashboard.project-years', { user_id })

  const data = {
    labels: monthLabels,
    datasets: [],
  }
  
  for (const year of await years) {
    data.datasets.push({
      label: year,
      data: (await api.get('dashboard.monthly-new-projects-count', { year, user_id })).data,
    })
  }
  
  return data
}

export const getProjectsTypeBreakdownChartData = async (user_id) => {
  const years = api.get('dashboard.project-years', { user_id })

  const data = {
    labels: projectTypeLabels,
    datasets: [],
  }
  
  for (const year of await years) {
    data.datasets.push({
      label: year,
      data: (await api.get('dashboard.projects-type-breakdown', { year, user_id })).data,
    })
  }
  
  return data
}

export const getTopUsers = async ({ from, to }, limit = 3) => {
  const params = {
    limit,
    from,
    to,
  }

  return api.get('dashboard.top-users', params)
}

export const getTopProjects = async ({ from, to }, limit = 3) => {
  const params = {
    limit,
    from,
    to,
  }
  
  return api.get('dashboard.top-projects', params)
}

export const getKpi = async ({ from, to }, user_id) => {
  const params = {
    from,
    to,
    user_id,
  }
  
  return api.get('dashboard.kpi', params)
}