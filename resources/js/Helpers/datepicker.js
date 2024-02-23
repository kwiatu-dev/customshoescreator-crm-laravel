import Datepicker from 'flowbite-datepicker/Datepicker'
import language from 'flowbite-datepicker/locales/pl'

const create = (input, date_start, callback) => {
  Datepicker.locales.pl = language.pl
  
  const datepicker = new Datepicker(input.value, {
    todayBtn: true,
    clearBtn: true,
    todayHighlight: true,
    language: 'pl',
    format: 'yyyy-mm-dd',
    defaultViewDate: new Date(date_start ?? 'today'),
  })
      
  input.value.addEventListener('changeDate', callback)

  return datepicker
}

export default {
  create,
}