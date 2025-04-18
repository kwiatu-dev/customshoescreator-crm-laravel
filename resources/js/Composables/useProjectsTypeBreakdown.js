import axios from 'axios'

export const useProjectsTypeBreakdown = async (year, user_id) => {
  try {
    const params = {
      year,
      user_id,
    }
    const response = await axios.get(route('dashboard.projects-type-breakdown'), { params })
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania Projects Type Breakdown:', error)
    return null
  }
}