import axios from 'axios'

export const useMonthlyCompletedProjectsCount = async (year) => {
  try {
    const params = {
      year,
    }
    const response = await axios.get(route('dashboard.monthly-completed-projects-count'), { params })
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania Monthly Completed Projects Count:', error)
    return null
  }
}