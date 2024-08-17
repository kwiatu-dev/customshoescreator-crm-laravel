export const options = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
    datalabels: {
      display: false,
    },
    tooltip: {
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
            label += new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(context.parsed.y)
          }
          return label
        },
      },
    },
  },
  scales: {
    x: {
      ticks: {
        color: 'rgb(156 163 175)',
        font: {
          size: 10,
        },
      },
    },
    y: {
      beginAtZero: true,
      ticks: {
        color: 'rgb(156 163 175)',
        font: {
          size: 10,
        },
        callback: (value, index) => {
          if (index === 0) {
            return ''
          }
  
          return new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(value)
        },
      },
    },
  },
}