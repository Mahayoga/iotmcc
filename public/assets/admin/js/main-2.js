
// Grafik chart dashboard suhu dan kelembapan
const ctxSuhu = document.getElementById('chartSuhu').getContext('2d');
const ctxKelembapan = document.getElementById('chartKelembapan').getContext('2d');

// Grafik suhu
const suhuChart = new Chart(ctxSuhu, {
  type: 'line',
  data: {
    labels: ['08:00', '09:00', '10:00', '11:00', '12:00'],
    datasets: [
      {
        label: 'Ruang 1',
        data: [27, 28, 30, 29, 31],
        borderColor: '#00A86B',
        backgroundColor: 'rgba(0, 168, 107, 0.1)',
        tension: 0.4,
        borderWidth: 3
      },
      {
        label: 'Ruang 2',
        data: [29, 30, 32, 33, 31],
        borderColor: '#FFD93D',
        backgroundColor: 'rgba(255, 217, 61, 0.1)',
        tension: 0.4,
        borderWidth: 3
      },
      {
        label: 'Ruang 3',
        data: [31, 32, 33, 34, 35],
        borderColor: '#FF6B6B',
        backgroundColor: 'rgba(255, 107, 107, 0.1)',
        tension: 0.4,
        borderWidth: 3
      }
    ]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        title: { display: true, text: 'Suhu (°C)', color: '#888' },
        beginAtZero: true
      },
      x: {
        title: { display: true, text: 'Waktu', color: '#888' }
      }
    },
    plugins: {
      legend: {
        display: true,
        labels: { usePointStyle: true, boxWidth: 8 }
      }
    }
  }
});

// Grafik kelembapan
const kelembapanChart = new Chart(ctxKelembapan, {
  type: 'line',
  data: {
    labels: ['08:00', '09:00', '10:00', '11:00', '12:00'],
    datasets: [
      {
        label: 'Ruang 1',
        data: [65, 58, 54, 60, 76],
        borderColor: '#00A86B',
        backgroundColor: 'rgba(0, 168, 107, 0.1)',
        tension: 0.4,
        borderWidth: 3
      },
      {
        label: 'Ruang 2',
        data: [68, 62, 55, 61, 67],
        borderColor: '#FFD93D',
        backgroundColor: 'rgba(255, 217, 61, 0.1)',
        tension: 0.4,
        borderWidth: 3
      },
      {
        label: 'Ruang 3',
        data: [70, 52, 43, 63, 72],
        borderColor: '#FF6B6B',
        backgroundColor: 'rgba(255, 107, 107, 0.1)',
        tension: 0.4,
        borderWidth: 3
      }
    ]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        title: { display: true, text: 'Kelembapan (%)', color: '#888' },
        beginAtZero: true,
        ticks: {
          callback: function (value) {
            return Math.round(value); // bulatkan angka tanpa koma
          }
        }
      },
      x: {
        title: { display: true, text: 'Waktu', color: '#888' }
      }
    },
    plugins: {
      legend: {
        display: true,
        labels: { usePointStyle: true, boxWidth: 8 }
      }
    }
  }
});