export const options = ({ local, currency, theme, showTicks, showGrid, stepSize }) => ({
  responsive: true,
  maintainAspectRatio: false,
  pointBackgroundColor: theme === 'dark' ? 'rgb(209 213 219)' : 'rgb(107 114 128)',
  pointBorderColor: theme === 'dark' ? 'rgb(209 213 219)' : 'rgb(107 114 128)',
  borderWidth: 1,
  tension: 0.5,
  fill: true,
  plugins: {
    legend: {
      display: true,
      position: 'bottom',
      onClick: null,
      labels: {
        color: theme === 'dark' ? 'rgb(209 213 219)' : 'black', 
      },
    },
    datalabels: {
      display: false,
    },
    tooltip: {
      displayColors: false,
      bodyColor: theme === 'dark' ? 'rgb(209 213 219)' : 'black',
      titleColor: theme === 'dark' ? 'rgb(209 213 219)' : 'black',
      backgroundColor: theme === 'dark' ? 'rgb(17 24 39)' : 'rgb(243 244 246)',
      padding: 10,
      callbacks: {
        label: (context) => {
          let label = context.dataset.label || ''
  
          if (label) {
            label += ': '
          }
          if (currency && context.parsed.y !== null) {
            label += new Intl.NumberFormat(local, { style: 'currency', currency: currency }).format(context.parsed.y)
          }
          else {
            label += context.parsed.y
          }

          return label
        },
      },
    },
  },
  scales: {
    x: {
      ticks: {
        display: showTicks,
        color: theme === 'dark' ? 'rgb(156 163 175)' : 'black',
        font: {
          size: 10,
        },
        stepSize: stepSize,
      },
      grid: {
        display: showGrid,
        color: theme === 'dark' ? 'rgb(55 65 81)' : 'rgb(229 231 235)',
      },
    },
    y: {
      beginAtZero: true,
      ticks: {
        display: showTicks,
        color: theme === 'dark' ? 'rgb(156 163 175)' : 'black',
        font: {
          size: 10,
        },
        stepSize: stepSize,
        callback: (value, index) => {
          if (index === 0) {
            return ''
          }

          if (currency) {
            return new Intl.NumberFormat(local, { style: 'currency', currency: currency }).format(value)
          }
          else {
            return value
          }
        },
      },
      grid: {
        display: showGrid,
        color: theme === 'dark' ? 'rgb(55 65 81)' : 'rgb(229 231 235)', 
      },
    },
  },
})