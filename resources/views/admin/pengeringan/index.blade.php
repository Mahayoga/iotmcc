@extends('admin.layouts.main')

@section('title', 'Ruang Pengeringan')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <!-- Page Header -->
      <style>
        .bi-fan-spin {
          animation: spin 1s linear infinite;
        }

        @keyframes spin {
          from {
            transform: rotate(0deg);
          }

          to {
            transform: rotate(360deg);
          }
        }
      </style>

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
                <small class="text-muted">*data yang ditampilkan adalah 30 data terakhir</small>
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
                <small class="text-muted">*data yang ditampilkan adalah 30 data terakhir</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Blower -->
      <div class="row mt-4">
        <!-- Status Blower -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0 text-center">
              <h5 class="card-title mb-1 mt-2 fw-semibold">Status Blower</h5>
              <small class="text-muted">Indikator Operasional Blower</small>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
              <div id="blower-indicator" class="mb-3">
                <i class="bi bi-fan fs-1 text-secondary" id="blower-icon"></i>
              </div>
              <h4 id="blower-status-text" class="fw-bold text-muted mb-3">Dalam pengerjaan ⏳</h4>

              <!-- Switch -->
              <div class="form-check form-switch d-flex flex-column align-items-center">
                <input class="form-check-input mb-2" type="checkbox" id="blower-switch" disabled>
                <label class="form-check-label text-muted fs-6" for="blower-switch" id="blower-switch-label">
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Durasi Blower Aktif -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0 text-center">
              <h5 class="card-title mb-1 mt-2 fw-semibold">Durasi Blower Aktif</h5>
              <small class="text-muted">Total waktu blower aktif hari ini</small>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
              {{-- <h4 id="durasi-blower" class="fw-bold text-success mb-3">Dalam pengerjaan ⏳</h4> --}}
              <p id="status-proses-blower" class="badge bg-secondary fs-6 px-3 py-2">Dalam pengerjaan ⏳</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@section('script')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
    const ctxKelembaban = document.getElementById('chartKelembaban')?.getContext('2d');

    let suhuChart = new Chart(ctxSuhu, {
      type: 'line',
      data: { datasets: [{ label: "Suhu (°C)", data: [], borderWidth: 2, borderColor: '#28a745', fill: false }], labels: [] },
      options: { responsive: true, scales: { y: { beginAtZero: true }, x: {} } }
    });

    let kelembabanChart = new Chart(ctxKelembaban, {
      type: 'line',
      data: { datasets: [{ label: "Kelembaban (%)", data: [], borderWidth: 2, borderColor: '#007bff', fill: false }], labels: [] },
      options: { responsive: true, scales: { y: { beginAtZero: true }, x: {} } }
    });

    function getDataSuhu() {
      $.get('{{ route('ruang-pengeringan.getDataSuhu', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {}, function (data) {
        if (data.status) {
          $('#suhu-rata-rata').text(data.dataAvgSuhu);
          $('#kelembaban-rata-rata').text(data.dataAvgKelembaban);

          // Status Suhu
          let suhuClass = $('#status-suhu-ruangan');
          suhuClass.removeClass('text-success text-warning text-danger');
          if (data.dataAvgSuhu > 25 && data.dataAvgSuhu < 30) suhuClass.text('Normal').addClass('text-success');
          else if (data.dataAvgSuhu >= 30 && data.dataAvgSuhu < 50) suhuClass.text('Peringatan').addClass('text-warning');
          else suhuClass.text('Bahaya').addClass('text-danger');

          // Status Kelembaban
          let kelembabanClass = $('#status-kelembaban-ruangan');
          kelembabanClass.removeClass('text-success text-warning text-danger');
          if (data.dataAvgKelembaban > 80) kelembabanClass.text('Normal').addClass('text-success');
          else if (data.dataAvgKelembaban > 60) kelembabanClass.text('Peringatan').addClass('text-warning');
          else kelembabanClass.text('Bahaya').addClass('text-danger');

          // Update Chart
          suhuChart.data.datasets[0].data = data.dataSuhu.reverse();
          suhuChart.data.labels = data.dataWaktuSuhu.reverse();
          suhuChart.update();

          kelembabanChart.data.datasets[0].data = data.dataKelembaban.reverse();
          kelembabanChart.data.labels = data.dataWaktuKelembaban.reverse();
          kelembabanChart.update();
        }
      });
    }

    // ---- Blower ----
    const blowerSwitch = document.getElementById('blower-switch');
    const blowerIcon = document.getElementById('blower-icon');
    const blowerStatusText = document.getElementById('blower-status-text');
    const blowerLabel = document.getElementById('blower-switch-label');
    let blowerStartTime = null;

    function updateBlowerSwitch(statusBlower, durasiAktif) {
      if (statusBlower == 1) {
        blowerSwitch.checked = true;
        blowerIcon.classList.add('text-success', 'bi-fan-spin');
        blowerIcon.classList.remove('text-secondary');
        blowerStatusText.textContent = 'Blower Aktif';
        blowerStatusText.classList.add('text-success');
        blowerStatusText.classList.remove('text-muted');
        blowerLabel.textContent = 'Matikan Blower';
        $('#status-proses-blower').text('Proses Pengeringan').removeClass('bg-secondary').addClass('bg-success');
        blowerStartTime = new Date();
      } else {
        blowerSwitch.checked = false;
        blowerIcon.classList.remove('text-success', 'bi-fan-spin');
        blowerIcon.classList.add('text-secondary');
        blowerStatusText.textContent = 'Blower Mati';
        blowerStatusText.classList.remove('text-success');
        blowerStatusText.classList.add('text-muted');
        blowerLabel.textContent = 'Nyalakan Blower';
        $('#status-proses-blower').text('Idle').removeClass('bg-success').addClass('bg-secondary');
        blowerStartTime = null;
      }
      $('#durasi-blower').text(durasiAktif + ' mnt');
    }

    function getDataBlower() {
      $.get('{{ route('ruang-pengeringan.getDataBlower', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {}, function (data) {
        if (data.status) updateBlowerSwitch(data.statusBlower, data.durasiAktif);
      });
    }

    blowerSwitch.addEventListener('change', function () {
      $.post('{{ route('ruang-pengeringan.toggleBlower', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {
        _token: '{{ csrf_token() }}'
      }, function (response) {
        if (response.status) getDataBlower();
        else {
          alert(response.message);
          blowerSwitch.checked = !blowerSwitch.checked;
        }
      });
    });

    // Timer durasi otomatis
    setInterval(() => {
      if (blowerStartTime) {
        let durasi = Math.floor((new Date() - blowerStartTime) / 60000);
        $('#durasi-blower').text(durasi + ' mnt');
      }
    }, 1000);

    // Interval update data
    getDataSuhu();
    getDataBlower();
    setInterval(getDataSuhu, 3000);
    setInterval(getDataBlower, 5000);
  </script>
@endsection