import Datepicker from 'flowbite-datepicker/Datepicker'
import language from 'flowbite-datepicker/locales/pl'

const create = (input, date_start, callback, format = 'yyyy-mm-dd') => {
  Datepicker.locales.pl = language.pl
  
  const datepicker = new Datepicker(input.value, {
    todayBtn: true,
    clearBtn: true,
    todayHighlight: true,
    language: 'pl',
    format: format,
    defaultViewDate: new Date(date_start ?? 'today'),
  })
      
  input.value.addEventListener('changeDate', callback)

  return datepicker
}

const range = (input, date_start, callback, format = 'yyyy-mm-dd') => {
  Datepicker.locales.pl = language.pl
  
  const datepicker = new Datepicker(input.value, {
    todayBtn: false,
    clearBtn: false,
    todayHighlight: true,
    language: 'pl',
    format: format,
    defaultViewDate: new Date(date_start ?? 'today'),
    pickLevel: 1,
  })
      
  input.value.addEventListener('changeDate', callback)

  return datepicker
}

export default {
  create,
  range,
}