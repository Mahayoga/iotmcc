@extends('admin.layouts.main')

@section('title', 'Ruang Pengeringan')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Ruang Pengeringan</h1>
          <p class="text-muted mb-0">Rekap Ruang Pengeringan Vanili Agrofilia Permata</p>
        </div>
      </div>

      <!-- Rekap Suhu & Kelembaban -->
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Rekap Ruang Pengeringan</h5>
              <small class="text-muted">Pantauan Kondisi di Ruang Pengeringan Vanili</small>
            </div>
            <div class="card-body">
              <div class="row gy-4">
                <!-- Card Suhu -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-1">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-success">
                          <i class="bi bi-thermometer-half fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h5 class="mb-0 fw-bold">Suhu rata - rata</h5>
                        </div>
                      </div>
                      <span class="badge bg-light text-success px-3 py-2" id="status-suhu-ruangan">Normal</span>
                    </div>
                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0"><span id="suhu-rata-rata">-</span> °C</h2>
                    </div>
                    <div class="gudang-footer">
                      <small class="text-muted"><i class="bi bi-cpu me-1"></i>Sensor: DHT22 (Suhu dan Kelembaban)</small>
                    </div>
                  </div>
                </div>

                <!-- Card Kelembaban -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-1">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-success">
                          <i class="bi bi-moisture fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h5 class="mb-0 fw-bold">Kelembaban rata - rata</h5>
                        </div>
                      </div>
                      <span class="badge bg-light text-success px-3 py-2" id="status-kelembaban-ruangan">Normal</span>
                    </div>
                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0"><span id="kelembaban-rata-rata">-</span> %</h2>
                    </div>
                    <div class="gudang-footer">
                      <small class="text-muted"><i class="bi bi-cpu me-1"></i>Sensor: DHT22 (Suhu dan Kelembaban)</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Grafik -->
      <div class="row mt-4">
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Suhu</h5>
              <small class="text-muted">Perubahan Suhu di Ruang Pengeringan</small>
            </div>
            <div class="card-body" style="height: 350px;">
              <canvas id="chartSuhu" style="width:100%; height:90%;"></canvas>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah 11 data terakhir</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Kelembaban</h5>
              <small class="text-muted">Perubahan Kelembaban di Ruang Pengeringan</small>
            </div>
            <div class="card-body" style="height: 350px;">
              <canvas id="chartKelembaban" style="width:100%; height:90%;"></canvas>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah 11 data terakhir</small>
              </div>
            </div>
          </div>
        </div>


        <div class="col-md-12">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Perbandingan Grafik Suhu dan Kelembaban</h5>
              <small class="text-muted">Perbandingan Suhu dan Kelembaban di Ruang Pengeringan</small>
            </div>
            <div class="card-body" style="height: 400px;">
              <canvas id="chartSuhuDanKelembaban" style="width:100%; height:90%;"></canvas>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah <span id="total-suhu-dan-kelembaban">-</span> data
                  terakhir</small>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Blower -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0 position-relative text-center">
              <div>
                <h5 class="card-title mb-1 mt-2 fw-semibold">Daftar Blower</h5>
                <small class="text-muted">Indikator Operasional Blower (Total 8 Unit)</small>
              </div>
              <div class="position-absolute bottom-0 end-0 mb-2 me-3"
                style="border: 1px solid #dee2e6; border-radius: 0.5rem; padding: 0.25rem 0.5rem; background-color: #f8f9fa;">
                <div class="form-check form-switch d-flex align-items-center m-0">
                  <input class="form-check-input" type="checkbox" id="switch-all" style="margin:0;">
                  <label class="form-check-label small text-muted fw-semibold ms-2 mb-0" for="switch-all">
                    Switch all
                  </label>
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="container">
                <!-- Baris Pertama: Blower 1-4 -->
                <div class="row mb-3">
                  @for ($i = 1; $i <= 4; $i++)
                    <div class="col-md-3 mb-2">
                      <div
                        class="d-flex flex-column justify-content-between align-items-center py-3 px-2 border rounded-3 shadow-sm h-100"
                        style="background:#f8f9fa;">
                        <div class="d-flex flex-column align-items-center mb-3">
                          <i id="blower-{{ $i }}" class="bi bi-fan mb-2" style="font-size: 2rem; color: gray;"></i>
                          <h6 class="mb-0 fw-semibold text-muted">Blower {{ $i }}</h6>
                        </div>

                        <div class="d-flex flex-column align-items-center">
                          <input class="form-check-input blower-switch mb-2" type="checkbox" id="switch-{{ $i }}"
                            data-id="{{ $i }}" style="margin:0;">
                          <label class="form-check-label fw-semibold text-muted blower-label small mb-0"
                            for="switch-{{ $i }}">
                            Mati
                          </label>
                        </div>
                      </div>
                    </div>
                  @endfor
                </div>

                <!-- Baris Kedua: Blower 5-8 -->
                <div class="row mb-3">
                  @for ($i = 5; $i <= 8; $i++)
                    <div class="col-md-3 mb-2">
                      <div
                        class="d-flex flex-column justify-content-between align-items-center py-3 px-2 border rounded-3 shadow-sm h-100"
                        style="background:#f8f9fa;">
                        <div class="d-flex flex-column align-items-center mb-3">
                          <i id="blower-{{ $i }}" class="bi bi-fan mb-2" style="font-size: 2rem; color: gray;"></i>
                          <h6 class="mb-0 fw-semibold text-muted">Blower {{ $i }}</h6>
                        </div>

                        <div class="d-flex flex-column align-items-center">
                          <input class="form-check-input blower-switch mb-2" type="checkbox" id="switch-{{ $i }}"
                            data-id="{{ $i }}" style="margin:0;">
                          <label class="form-check-label fw-semibold text-muted blower-label small mb-0"
                            for="switch-{{ $i }}">
                            Mati
                          </label>
                        </div>
                      </div>
                    </div>
                  @endfor
                </div>
              </div>

              <!-- Deskripsi -->
              <div class="mt-4 text-start">
                <h6 class="fw-bold">Deskripsi</h6>
                <p class="mb-1">
                  <span class="badge bg-success"
                    style="width:15px; height:15px; background-image: linear-gradient(to right, #A9DA2E, #6EA017); border-color: #A9DA2E;">&nbsp;</span>
                  Blower Menyala
                </p>
                <p class="mb-1">
                  <span class="badge bg-secondary me-2" style="width:15px; height:15px;">&nbsp;</span>
                  Blower Mati
                </p>
                <small class="text-muted">*Gunakan tombol untuk menyalakan atau mematikan blower</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <style>
        .form-check-input {
          width: 3rem;
          height: 1.5rem;
          cursor: pointer;
          transition: background-color 0.3s, border-color 0.3s;
        }

        .form-check-input:checked {
          background-color: #6EA017 !important;
          border-color: #6EA017 !important;
          background-image: linear-gradient(to right, #A9DA2E, #6EA017);
          transition: background-color 0.3s, border-color 0.3s;
        }
      </style>

  </main>
@endsection

@section('script')
  <script>
    const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
    const ctxKelembaban = document.getElementById('chartKelembaban')?.getContext('2d');
    const ctxSuhuDanKelembaban = document.getElementById('chartSuhuDanKelembaban')?.getContext('2d');

    // Event blower
    document.querySelectorAll('.blower-switch').forEach((switchEl) => {
      switchEl.addEventListener('change', function () {
        const label = this.nextElementSibling;
        const blowerIcon = document.getElementById('blower-' + this.dataset.id);

        if (this.checked) {
          label.textContent = 'Hidup';
          label.style.color = '#6EA017';
          blowerIcon.style.color = '#6EA017';
          blowerIcon.classList.add('bi-fan-spin');
        } else {
          label.textContent = 'Mati';
          label.style.color = 'gray';
          blowerIcon.style.color = 'gray';
          blowerIcon.classList.remove('bi-fan-spin');
        }
      });
    });

    // Event tombol “Hidupkan Semua”
    const switchAll = document.getElementById('switch-all');
    switchAll.addEventListener('change', function () {
      const allSwitches = document.querySelectorAll('.blower-switch');
      const turnOn = this.checked;
      allSwitches.forEach((switchEl) => {
        if (switchEl.checked !== turnOn) switchEl.click();
      });
      this.nextElementSibling.textContent = turnOn ? 'switch of' : 'switch on';
    });

    // Animasi kipas
    const style = document.createElement('style');
    style.innerHTML = `
        .bi-fan-spin {
          animation: spin 1s linear infinite;
        }
        @keyframes spin {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }
      `;
    document.head.appendChild(style);

    let suhuChart = new Chart(ctxSuhu, {
      type: 'line',
      data: {
        datasets: [{
          label: "Suhu (°C)",
          data: [],
          backgroundColor: '#C8F76A33',
          borderColor: '#C8F76A',
          pointBorderColor: '#0f172abf',
          fill: true
        }],
        labels: []
      },

      options: {
        responsive: true,
        scales: {
          y: { title: { display: true, text: 'Suhu (°C)', color: '#888' }, beginAtZero: true },
          x: { title: { display: true, text: 'Waktu', color: '#888' } }
        },
        animation: {
          duration: 800,
        }
      }
    });

    let kelembabanChart = new Chart(ctxKelembaban, {
      type: 'line',
      data: {
        datasets: [{
          label: "Kelembaban (%)",
          data: [],
          backgroundColor: '#C8F76A33',
          borderColor: '#C8F76A',
          pointBorderColor: '#0f172abf',
          fill: true
        }],
        labels: []
      },
      options: {
        responsive: true,
        scales: {
          y: { title: { display: true, text: 'Kelembaban (%)', color: '#888' }, beginAtZero: true },
          x: { title: { display: true, text: 'Waktu', color: '#888' } }
        },
        animation: {
          duration: 800,
        }
      }
    });

    let suhuDanKelembabanChart = new Chart(ctxSuhuDanKelembaban, {
      type: 'line',
      data: {
        datasets: [
          {
            label: "Kelembaban (%)",
            data: [],
            backgroundColor: 'rgba(135, 206, 235, 0.25)',
            borderColor: 'rgba(135, 206, 235, 0.8)',
            pointBorderColor: '#0f172abf',
            fill: true
          },
          {
            label: "Suhu (°C)",
            data: [],
            backgroundColor: 'rgba(255, 182, 193, 0.25)',
            borderColor: 'rgba(255, 182, 193, 0.8)',
            pointBorderColor: '#0f172abf',
            fill: true
          }
        ],
        labels: []
      },
      options: {
        responsive: true,
        scales: {
          y: {
            title: {
              display: true,
              text: 'Data Suhu dan Kelembaban',
              color: '#666'
            },
            beginAtZero: true,
            grid: { color: 'rgba(200,200,200,0.2)' }
          },
          x: {
            title: {
              display: true,
              text: 'Waktu',
              color: '#666'
            },
            grid: { color: 'rgba(200,200,200,0.2)' }
          }
        },
        animation: { duration: 800 },
        plugins: {
          legend: {
            labels: {
              color: '#444',
              font: { size: 13 }
            }
          }
        }
      }
    });


    function getDataSensor() {
      $.get('{{ route('ruang-pengeringan.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {

      }, function (data, status) {
        if (data.status == true) {
          let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
          let classListKelembaban = document.getElementById('status-kelembaban-ruangan').classList;

          let suhuTotal = 0;
          let totalDataSuhu = 0;
          let kelTotal = 0;
          let totalDataKel = 0;
          data.dataSensor.forEach(element => {
            if (element.flag_sensor.includes('suhu')) {
              element.value.forEach(element2 => {
                suhuTotal += parseFloat(element2);
                totalDataSuhu += 1;
              });
            }
          });

          data.dataSensor.forEach(element => {
            if (element.flag_sensor.includes('kelembaban')) {
              element.value.forEach(element2 => {
                kelTotal += parseFloat(element2);
                totalDataKel += 1;
              });
            }
          });

          let rataRataSuhu = (suhuTotal / totalDataSuhu).toFixed(2);
          let rataRataKel = (kelTotal / totalDataKel).toFixed(2);

          $('#suhu-rata-rata')[0].innerHTML = rataRataSuhu;
          $('#kelembaban-rata-rata')[0].innerHTML = rataRataKel;

          if (rataRataSuhu > 20 && rataRataSuhu < 30) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Normal';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-success');
          } else if (rataRataSuhu > 30 && rataRataSuhu < 50) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          } else if (rataRataSuhu > 50 && rataRataSuhu < 100) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Bahaya';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-danger');
          } else {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          }

          if (rataRataKel > 80) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Normal';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-success');
          } else if (rataRataKel > 60) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Peringatan';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-warning');
          } else if (rataRataKel > 0) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Bahaya';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          } else {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Kesalahan!';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          }

          let dataSuhuTemp = [
            [],
            []
          ];
          let dataResultSuhuTemp = [];
          data.dataSensor.forEach(element => {
            if (element.flag_sensor.includes('suhu_1')) {
              dataSuhuTemp[0].push(element.value);
            }

            if (element.flag_sensor.includes('suhu_2')) {
              dataSuhuTemp[1].push(element.value);
            }
          });

          for (var i = 0; i < dataSuhuTemp[0][0].length; i++) {
            dataResultSuhuTemp.push((parseInt(dataSuhuTemp[0][0][i]) + parseInt(dataSuhuTemp[1][0][0])) / 2);
          }

          let dataKelTemp = [
            [],
            []
          ];
          let dataResultKelTemp = [];
          data.dataSensor.forEach(element => {
            if (element.flag_sensor.includes('kelembaban_1')) {
              dataKelTemp[0].push(element.value);
            }

            if (element.flag_sensor.includes('kelembaban_2')) {
              dataKelTemp[1].push(element.value);
            }
          });

          for (var i = 0; i < dataKelTemp[0][0].length; i++) {
            dataResultKelTemp.push((parseInt(dataKelTemp[0][0][i]) + parseInt(dataKelTemp[1][0][i])) / 2);
            // console.log(dataKelTemp[1][0][i]);
          }

          // console.log(dataKelTemp);
          // console.log(dataResultKelTemp);

          suhuChart.data.labels = data.dataWaktuSensor[1].value;
          suhuChart.data.datasets[0].data = dataResultSuhuTemp;

          kelembabanChart.data.labels = data.dataWaktuSensor[0].value;
          kelembabanChart.data.datasets[0].data = dataResultKelTemp;

          suhuDanKelembabanChart.data.labels = data.dataWaktuSensor[1].value;
          suhuDanKelembabanChart.data.datasets[0].data = dataResultKelTemp;
          suhuDanKelembabanChart.data.datasets[1].data = dataResultSuhuTemp;

          $('#total-suhu').text(dataResultSuhuTemp.length);
          $('#total-kelembaban').text(dataResultKelTemp.length);
          $('#total-suhu-dan-kelembaban').text(dataResultSuhuTemp.length);

          suhuChart.update();
          kelembabanChart.update();
          suhuDanKelembabanChart.update();
        }
      });
    }
    setInterval(getDataSensor, 1000);
  </script>
@endsection