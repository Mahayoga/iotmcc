@extends('admin.layouts.main')

@section('title', 'Alat Bleaching')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Alat Bleaching</h1>
          <p class="text-muted mb-0">Rekap Alat Bleaching Vanili</p>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-xl-6 col-lg-6 col-md-12">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Suhu Rata-Rata</h5>
              <small class="text-muted">Pantauan kondisi suhu alat bleaching</small>
            </div>

            <div class="card-body">
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

                <div class="gudang-main mt-3 text-center">
                  <h2 class="fw-bold mb-0"><span id="suhu-rata-rata">-</span> °C</h2>
                </div>

                <div class="gudang-footer text-center">
                  <small class="text-muted">
                    <i class="bi bi-cpu me-1"></i>Sensor: DHT22 (Suhu)
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-12">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Suhu Alat Bleaching</h5>
              <small class="text-muted">Perubahan suhu dalam 11 pembacaan terakhir</small>
            </div>

            <div class="card-body">
              <canvas id="chartSuhu" height="150"></canvas>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah 11 data terakhir</small>
              </div>
            </div>
          </div>
        </div>
      </div>

  <div class="row g-4 mb-4">
  <!-- Timer Bleaching -->
  <div class="col-xl-6 col-lg-6 col-md-12">
    <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
      <div class="card-header bg-transparent border-0">
        <h5 class="card-title mb-1 mt-2">Timer Bleaching</h5>
        <small class="text-muted">Hitung mundur proses Bleaching</small>
      </div>

      <div class="card-body text-center d-flex flex-column justify-content-center">
        <h1 id="timer-display" class="fw-bold display-3 text-danger mb-4">00:00</h1>
        <div class="d-flex justify-content-center">
          <button id="stop-timer" class="btn btn-danger px-4">Hentikan</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Informasi Proses -->
  <div class="col-xl-6 col-lg-6 col-md-12">
    <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
      <div class="card-header bg-transparent border-0">
        <h5 class="card-title mb-1 mt-2">Informasi Proses</h5>
        <small class="text-muted">Detail waktu dan status proses Bleaching</small>
      </div>

      <div class="card-body d-flex flex-column justify-content-center">
        <div class="row">
          <div class="col-md-6">
            <p><b>Waktu Mulai:</b> <span id="waktu-mulai">-</span></p>
            <p><b>Perkiraan Selesai:</b> <span id="waktu-selesai">-</span></p>
            <p><b>Status:</b> <span id="status-proses" class="badge bg-secondary">Menunggu</span></p>
          </div>
          <div class="col-md-6">
            <label for="durasi-input" class="form-label mb-1 fw-bold">Durasi Bleaching (menit)</label>
            <div class="input-group">
              <input type="number" id="durasi-input" class="form-control" placeholder="Masukkan durasi">
              <button id="set-durasi" class="btn btn-primary">Atur</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  </main>
@endsection

@section('script')
  <script>

    let countdown;
  let totalSeconds = 0;
  let isRunning = false;

  const timerDisplay = document.getElementById('timer-display');
  const waktuMulai = document.getElementById('waktu-mulai');
  const waktuSelesai = document.getElementById('waktu-selesai');
  const statusProses = document.getElementById('status-proses');
  const durasiInput = document.getElementById('durasi-input');

  document.getElementById('set-durasi').addEventListener('click', function() {
    const durasiMenit = parseInt(durasiInput.value);

    if (isNaN(durasiMenit) || durasiMenit <= 0) {
      alert('Masukkan durasi yang valid (lebih dari 0 menit)');
      return;
    }

    if (isRunning) {
      clearInterval(countdown);
    }

    totalSeconds = durasiMenit * 60;
    isRunning = true;

    const startTime = new Date();
    const endTime = new Date(startTime.getTime() + totalSeconds * 1000);

    waktuMulai.textContent = startTime.toLocaleTimeString();
    waktuSelesai.textContent = endTime.toLocaleTimeString();
    statusProses.textContent = 'Berlangsung ⏳';
    statusProses.className = 'badge bg-warning text-dark';

    updateDisplay(totalSeconds);

    countdown = setInterval(() => {
      totalSeconds--;
      updateDisplay(totalSeconds);

      if (totalSeconds <= 0) {
        clearInterval(countdown);
        isRunning = false;
        statusProses.textContent = 'Selesai ✅';
        statusProses.className = 'badge bg-success';
      }
    }, 1000);
  });

  document.getElementById('stop-timer').addEventListener('click', function() {
    if (isRunning) {
      clearInterval(countdown);
      isRunning = false;
      statusProses.textContent = 'Dihentikan ⛔';
      statusProses.className = 'badge bg-danger';
    }
  });

  function updateDisplay(seconds) {
    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
    const s = (seconds % 60).toString().padStart(2, '0');
    timerDisplay.textContent = `${m}:${s}`;
  }

  const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');

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
          y: { title: { display: true, text: 'Suhu (°C)', color: '#888' }, beginAtZero: false }, 
          x: { title: { display: true, text: 'Waktu (pada 2025-10-26)', color: '#888' } }
        },
        animation: {
          duration: 800,
        }
      }
    });

   function getDataSensor() {
      $.get('{{ route('alat-bleaching.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', function(data, status) {
        if (data.status === true) {
          
          let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
          let rataRataSuhu = parseFloat(data.rataRataSuhu_7_10);
          $('#suhu-rata-rata').text(rataRataSuhu.toFixed(2));
          $('.card-header small:contains("Pantauan kondisi suhu alat bleaching")')
              .text("Rata-rata jam 07:00 - 10:00 (26-10-2025)");

          classListSuhu.remove('text-success', 'text-warning', 'text-danger', 'text-info');
          if(rataRataSuhu >= 85 && rataRataSuhu <= 95) {
              $('#status-suhu-ruangan')[0].innerHTML = 'Normal';
              classListSuhu.add('text-success');

          } else if (rataRataSuhu > 100) {
              $('#status-suhu-ruangan')[0].innerHTML = 'Bahaya';
              classListSuhu.add('text-danger');
          } else {
              $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
              classListSuhu.add('text-warning');
          }

          if (data.graphSuhu && data.graphWaktu) {
            suhuChart.data.labels = data.graphWaktu; 
            suhuChart.data.datasets[0].data = data.graphSuhu.map(v => parseFloat(v));
            suhuChart.update();
          }
          
          $('.card-header small:contains("24 jam terakhir")')
              .text("Perubahan suhu pada 26-10-2025");
          $('.card-body small:contains("*data yang ditampilkan adalah data 24 jam terakhir")')
              .text("*Data yang ditampilkan adalah semua data dari 26-10-2025");
        }
      });
    }
    getDataSensor();
    setInterval(getDataSensor, 60000); 
  </script>
@endsection