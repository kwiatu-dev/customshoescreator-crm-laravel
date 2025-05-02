const get = async (routeName, params) => {
  const { default: axios } = await import('axios')
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