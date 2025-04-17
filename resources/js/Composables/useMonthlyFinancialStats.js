import axios from 'axios'

export const useMonthlyFinancialStats = async (year, user_id) => {
  try {
    const params = {
      year,
      user_id
    }
    const response = await axios.get(route('dashboard.monthly-financial-stats'), { params })
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania Monthly Financial Stats:', error)
    return null
  }
}