export const useQueryParams = () => { 
  const urlSearchParams = new URLSearchParams(window.location.search)
  let params = {}
    
  urlSearchParams.forEach((value, key) => {
    params[key] = value
  })

  return params
}