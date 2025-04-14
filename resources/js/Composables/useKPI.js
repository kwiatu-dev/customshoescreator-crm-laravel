import axios from 'axios'

export const useKPI = async ({ from, to }) => {
  try {
    const response = await axios.get('/api/dashboard/kpi', {
      params: { from, to },
    })
    return response.data
  } 
  catch (error) {
    console.error('Błąd podczas pobierania KPI:', error)
    return null
  }
}