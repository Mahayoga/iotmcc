// // grafik monitoring suhu dan kelembapan
// const grafikSuhu = JSON.parse('<?= json_encode($grafikSuhu ?? []); ?>');
// const grafikKelembapan = JSON.parse('<?= json_encode($grafikKelembapan ?? []); ?>');

// // grafik suhu
// const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
// if (ctxSuhu && Object.keys(grafikSuhu).length > 0) {
//   const suhuDatasets = [];

//   Object.keys(grafikSuhu).forEach((namaRuangan, index) => {
//     suhuDatasets.push({
//       label: namaRuangan,
//       data: grafikSuhu[namaRuangan].map(item => item.nilai),
//       borderColor: ['#00A86B', '#FFD93D', '#FF6B6B'][index] || '#888',
//       backgroundColor: ['rgba(0,168,107,0.1)', 'rgba(255,217,61,0.1)', 'rgba(255,107,107,0.1)'][index] || 'rgba(136,136,136,0.1)',
//       tension: 0.4,
//       borderWidth: 3
//     });
//   });

//   new Chart(ctxSuhu, {
//     type: 'line',
//     data: {
//       labels: grafikSuhu[Object.keys(grafikSuhu)[0]].map(item => item.waktu),
//       datasets: suhuDatasets
//     },
//     options: {
//       responsive: true,
//       scales: {
//         y: {
//           title: { display: true, text: 'Suhu (°C)', color: '#888' },
//           beginAtZero: true
//         },
//         x: {
//           title: { display: true, text: 'Waktu', color: '#888' }
//         }
//       }
//     }
//   });
// }

// // grafik kelembapan
// const ctxKelembapan = document.getElementById('chartKelembapan')?.getContext('2d');
// if (ctxKelembapan && Object.keys(grafikKelembapan).length > 0) {
//   const kelembapanDatasets = [];

//   Object.keys(grafikKelembapan).forEach((namaRuangan, index) => {
//     kelembapanDatasets.push({
//       label: namaRuangan,
//       data: grafikKelembapan[namaRuangan].map(item => item.nilai),
//       borderColor: ['#00A86B', '#FFD93D', '#FF6B6B'][index] || '#888',
//       backgroundColor: ['rgba(0,168,107,0.1)', 'rgba(255,217,61,0.1)', 'rgba(255,107,107,0.1)'][index] || 'rgba(136,136,136,0.1)',
//       tension: 0.4,
//       borderWidth: 3
//     });
//   });

//   new Chart(ctxKelembapan, {
//     type: 'line',
//     data: {
//       labels: grafikKelembapan[Object.keys(grafikKelembapan)[0]].map(item => item.waktu),
//       datasets: kelembapanDatasets
//     },
//     options: {
//       responsive: true,
//       scales: {
//         y: {
//           title: { display: true, text: 'Kelembapan (%)', color: '#888' },
//           beginAtZero: true
//         },
//         x: {
//           title: { display: true, text: 'Waktu', color: '#888' }
//         }
//       }
//     }
//   });
// }
