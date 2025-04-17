import axios from 'axios'

export const useMonthlyCompletedProjectsCount = async (year, user_id) => {
  try {
    const params = {
      year,
      user_id,
    }
    const response = await axios.get(route('dashboard.monthly-completed-projects-count'), { params })
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania Monthly Completed Projects Count:', error)
    return null
  }
}