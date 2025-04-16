import axios from 'axios'

export const useMonthlyNewProjectsCount = async (year) => {
  try {
    const params = {
      year,
    }
    const response = await axios.get(route('dashboard.monthly-new-projects-count'), { params })
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania Monthly New Projects Count:', error)
    return null
  }
}