export const colors = ({ theme }) => {
  if (theme === 'dark') {
    return [
      {
        borderColor: '#ff6384',
        backgroundColor: 'rgba(255, 99, 132, {opacity})',
      },
      {
        borderColor: '#36a2eb',
        backgroundColor: 'rgba(54, 162, 235, {opacity})',
      },
      {
        borderColor: '#ffce56',
        backgroundColor: 'rgba(255, 206, 86, {opacity})',
      },
      {
        borderColor: '#4bc0c0',
        backgroundColor: 'rgba(75, 192, 192, {opacity})',
      },
      {
        borderColor: '#9966ff',
        backgroundColor: 'rgba(153, 102, 255, {opacity})',
      },
      {
        borderColor: '#ff9f40',
        backgroundColor: 'rgba(255, 159, 64, {opacity})',
      },
      {
        borderColor: '#e74c3c',
        backgroundColor: 'rgba(231, 76, 60, {opacity})',
      },
      {
        borderColor: '#2ecc71',
        backgroundColor: 'rgba(46, 204, 113, {opacity})',
      },
      {
        borderColor: '#3498db',
        backgroundColor: 'rgba(52, 152, 219, {opacity})',
      },
    ]
  } else if (theme === 'light') {
    return [
      {
        borderColor: '#e67e22',
        backgroundColor: 'rgba(230, 126, 34, {opacity})',
      },
      {
        borderColor: '#1abc9c',
        backgroundColor: 'rgba(26, 188, 156, {opacity})',
      },
      {
        borderColor: '#9b59b6',
        backgroundColor: 'rgba(155, 89, 182, {opacity})',
      },
      {
        borderColor: '#34495e',
        backgroundColor: 'rgba(52, 73, 94, {opacity})',
      },
      {
        borderColor: '#f1c40f',
        backgroundColor: 'rgba(241, 196, 15, {opacity})',
      },
      {
        borderColor: '#e74c3c',
        backgroundColor: 'rgba(231, 76, 60, {opacity})',
      },
      {
        borderColor: '#2ecc71',
        backgroundColor: 'rgba(46, 204, 113, {opacity})',
      },
      {
        borderColor: '#3498db',
        backgroundColor: 'rgba(52, 152, 219, {opacity})',
      },
      {
        borderColor: '#95a5a6',
        backgroundColor: 'rgba(149, 165, 166, {opacity})',
      },
    ]
  }
}