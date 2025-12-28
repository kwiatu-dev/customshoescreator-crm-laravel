const CHATBOT_URL = import.meta.env.VITE_CHATBOT_URL
const FILL_PARENT_FORM = 'FILL_PARENT_FORM'
const FILL_PARENT_FORM_SUCCESS = 'FILL_PARENT_FORM_SUCCESS'

let currentHandler = null

export const registerFormPrefillListener = (inertiaForm) => {
  currentHandler = (event) => {
    const { type, payload, requestId } = event.data

    if (type === FILL_PARENT_FORM) {
      const { data } = payload

      Object.keys(data).forEach((key) => {
        if (Object.hasOwn(inertiaForm, key)) {
          inertiaForm[key] = data[key]
        }
      })

      if (event.source) {
        const cleanData = JSON.parse(JSON.stringify(inertiaForm.data()))

        event.source.postMessage({
          type: FILL_PARENT_FORM_SUCCESS,
          payload: cleanData,
          requestId: requestId,
        }, CHATBOT_URL)
      }
    }
  }

  window.addEventListener('message', currentHandler)
}

export const unregisterFormPrefillListener = () => {
  if (currentHandler) {
    window.removeEventListener('message', currentHandler)
    currentHandler = null
  }
}