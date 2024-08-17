export const options = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      enabled: false,
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
          if (context.parsed.y !== null) {
            label += new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(context.parsed)
          }
          return label
        },
      },
    },
    datalabels: { 
      display: (context) => {
        const meta = context.chart.getDatasetMeta(0)
        
        if (meta.data[context.dataIndex].hidden === true) {
          return false
        }
      },
      formatter: (value, _) => {
        return new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(value)
      },
      font: {
        weight: 'bold',
        size: 20,
      },
      color: 'rgb(156 163 175)',
    },
  },
}