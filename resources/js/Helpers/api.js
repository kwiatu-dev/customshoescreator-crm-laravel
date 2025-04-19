import axios from 'axios'

const get = async (routeName, params) => {
  try {
    const response = await axios.get(route(routeName), { params })
    return response.data
  } 
  catch (error) {
    console.error('Błąd podczas pobierania danych:', error)
    return null
  }
}

export default {
  get,
}