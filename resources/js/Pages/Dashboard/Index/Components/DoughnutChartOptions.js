export const options = ({ local, currency, theme }) => ({
  responsive: true,
  maintainAspectRatio: false,
  cutout: '60%',
  spacing: 1,
  borderRadius: 8,
  borderWidth: 1,
  borderColor: theme === 'dark' ? 'rgb(55 65 81)' : 'rgb(243 244 246)',
  plugins: {
    legend: {
      display: true,
      position: 'bottom',
    },
    tooltip: {
      enabled: true,
      displayColors: false,
      bodyColor: 'rgb(209 213 219)',
      titleColor: 'rgb(209 213 219)',
      backgroundColor: 'rgb(17 24 39)',
      padding: 10,
      callbacks: {
        label: (context) => {
          let label = context.dataset.label || ''
    
          if (label) {
            label += ': '
          }

          if (currency && context.parsed !== null) {
            label += new Intl.NumberFormat(local, { style: 'currency', currency: currency }).format(context.parsed)
          }
          else {
            label += context.parsed
          }

          return label
        },
      },
    },
    datalabels: { 
      anchor: 'center',
      align: 'center',
      display: (context) => {
        const meta = context.chart.getDatasetMeta(0)
        
        if (meta.data[context.dataIndex].hidden === true) {
          return false
        }
      },
      formatter: (value, _) => {
        if (currency) {
          return new Intl.NumberFormat(local, { style: 'currency', currency: currency }).format(value)
        }
        else {
          return value
        }
      },
      font: {
        weight: 'bold',
        size: 30,
      },
      color: theme === 'dark' ? 'rgb(209 213 219)' : 'white',
    },
  },
})