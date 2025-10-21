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
                <small class="text-muted">*data yang ditampilkan adalah <span id="total-suhu-dan-kelembaban">-</span> data terakhir</small>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Blower -->
      <div class="row mt-4">
        {{-- <p id="status-proses-blower" class="badge bg-secondary fs-6 px-3 py-2">
          Dalam pengerjaan ⏳
        </p> --}}
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
                @for ($i = 1; $i <= 8; $i++)
                  <div class="d-flex justify-content-between align-items-center py-3 px-3 mb-2 border rounded-3 shadow-sm"
                    style="background:#f8f9fa;">
                    <div class="d-flex align-items-center">
                      <i id="blower-{{ $i }}" class="bi bi-fan me-3" style="font-size: 1.5rem; color: gray;"></i>
                      <h6 class="mb-0 fw-semibold text-muted">Blower {{ $i }}</h6>
                    </div>

                    <div class="form-check form-switch d-flex align-items-center">
                      <input class="form-check-input blower-switch me-2" type="checkbox" id="switch-{{ $i }}"
                        data-id="{{ $i }}">
                      <label class="form-check-label fw-semibold text-muted blower-label" for="switch-{{ $i }}"
                        style="width: 50px; display: inline-block; text-align: center;">Mati</label>
                    </div>
                  </div>
                @endfor
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

    // Event untuk blower
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
          backgroundColor: '#0f172abf',
          borderColor: '#C8F76A'
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
          backgroundColor: '#0f172abf',
          borderColor: '#C8F76A'
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
        datasets: [{
          label: "Kelembaban (%)",
          data: [],
          backgroundColor: '#FFFFFF',
          borderColor: '#1a91c4'
        }, {
          label: "Suhu (°C)",
          data: [],
          backgroundColor: '#FFFFFF',
          borderColor: '#d44a24'
        }],
        labels: []
      },
      options: {
        responsive: true,
        scales: {
          y: { title: { display: true, text: 'Data Suhu dan Kelembaban', color: '#888' }, beginAtZero: true },
          x: { title: { display: true, text: 'Waktu', color: '#888' } }
        },
        animation: {
          duration: 800,
        }
      }
    });

    function getDataSensor() {
      $.get('{{ route('ruang-pengeringan.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {

      }, function (data, status) {
        if (data.status == true) {
          let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
          let classListKelembaban = document.getElementById('status-kelembaban-ruangan').classList;
          $('#suhu-rata-rata')[0].innerHTML = data.dataAvgSuhu[data.dataAvgSuhu.length - 1];
          $('#kelembaban-rata-rata')[0].innerHTML = data.dataAvgKelembaban[data.dataAvgKelembaban.length - 1];

          if (data.dataAvgSuhu > 25 && data.dataAvgSuhu < 30) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Normal';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-success');
          } else if (data.dataAvgSuhu > 30 && data.dataAvgSuhu < 50) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          } else if (data.dataAvgSuhu > 50 && data.dataAvgSuhu < 100) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Bahaya';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-danger');
          } else {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          }

          if (data.dataAvgKelembaban > 80) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Normal';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-success');
          } else if (data.dataAvgKelembaban > 60) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Peringatan';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-warning');
          } else if (data.dataAvgKelembaban > 0) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Bahaya';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          } else {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Kesalahan!';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          }

          suhuChart.data.labels = data.dataWaktuSuhu[0];
          suhuChart.data.datasets[0].data = data.dataAvgSuhu;

          kelembabanChart.data.labels = data.dataWaktuKelembaban[0];
          kelembabanChart.data.datasets[0].data = data.dataAvgKelembaban;

          suhuDanKelembabanChart.data.labels = data.dataWaktuSuhu[0];
          suhuDanKelembabanChart.data.datasets[0].data = data.dataAvgKelembaban;
          suhuDanKelembabanChart.data.datasets[1].data = data.dataAvgSuhu;

          suhuChart.update();
          kelembabanChart.update();
          suhuDanKelembabanChart.update();
        }
      });
    }
    setInterval(getDataSensor, 1000);
  </script>
@endsection