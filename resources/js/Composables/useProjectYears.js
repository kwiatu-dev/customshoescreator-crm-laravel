import axios from 'axios'

export const useProjectYears = async () => {
  try {
    const response = await axios.get(route('dashboard.project-years'))
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania Project Years:', error)
    return null
  }
}