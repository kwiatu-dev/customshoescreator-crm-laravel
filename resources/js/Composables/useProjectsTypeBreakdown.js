import axios from 'axios'

export const useProjectsTypeBreakdown = async (range) => {
  try {
    const params = {
      ...(range && { from: range.from, to: range.to }),
    }
    const response = await axios.get(route('dashboard.projects-type-breakdown'), { params })
    return response.data
  } catch (error) {
    console.error('Błąd podczas pobierania Projects Type Breakdown:', error)
    return null
  }
}