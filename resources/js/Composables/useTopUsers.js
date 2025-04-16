import axios from 'axios'

export const useTopUsers = async (range, limit = 3) => {
  try {
    const params = {
      limit,
      ...(range && { from: range.from, to: range.to }),
    }
    const response = await axios.get('/api/dashboard/top-users', { params })
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania TOP Users:', error)
    return null
  }
}