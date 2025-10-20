@extends('admin.layouts.main')

@section('title', 'Ruang Blanching')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <!-- Page Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Ruang Blanching</h1>
          <p class="text-muted mb-0">Rekap Ruang Blanching Vanili Agrofilia Permata</p>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <!-- CARD SUHU RATA-RATA -->
        <div class="col-xl-6 col-lg-6 col-md-12">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Suhu Rata-Rata</h5>
              <small class="text-muted">Pantauan kondisi suhu ruang Blanching</small>
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
                    <i class="bi bi-cpu me-1"></i>Sensor: DHT22 (Suhu & Kelembaban)
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CARD GRAFIK SUHU -->
        <div class="col-xl-6 col-lg-6 col-md-12">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Suhu Ruang Blanching</h5>
              <small class="text-muted">Perubahan suhu dalam 30 pembacaan terakhir</small>
            </div>

            <div class="card-body">
              <canvas id="chartSuhu" height="150"></canvas>
            </div>
          </div>
        </div>
      </div>


      <!--cardd timer perebusan-->
      <div class="row g-4 mb-4">
        <div class="col-xl-6 col-lg-6 col-md-12">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Timer Blanching</h5>
              <small class="text-muted">Hitung mundur proses Blanching</small>
            </div>

            <div class="card-body text-center d-flex flex-column justify-content-center">
              <h1 id="timer-display" class="fw-bold display-3 text-danger mb-4">00:00</h1>
              <div class="d-flex justify-content-center gap-3">
                <button id="start-timer" class="btn btn-success px-4">Mulai</button>
                <button id="stop-timer" class="btn btn-danger px-4">Hentikan</button>
              </div>
            </div>
          </div>
        </div>

        <!--card informasi proses-->
        <div class="col-xl-6 col-lg-6 col-md-12">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Informasi Proses</h5>
              <small class="text-muted">Detail waktu dan status proses Blanching</small>
            </div>

            <div class="card-body d-flex flex-column justify-content-center">
              <div class="row">
                <div class="col-md-6">
                  <p><b>Waktu Mulai:</b> <span id="waktu-mulai">-</span></p>
                  <p><b>Perkiraan Selesai:</b> <span id="waktu-selesai">-</span></p>
                  <p><b>Status:</b> <span id="status-proses" class="badge bg-secondary">Menunggu</span></p>
                </div>
                <div class="col-md-6">
                  <label for="durasi-input" class="form-label mb-1 fw-bold">Durasi Blanching (menit)</label>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
    let timerInterval;
    let waktuMulai, waktuSelesai;

    function updateStatus(status) {
      const el = $('#status-proses');
      el.removeClass('bg-secondary bg-success bg-danger');
      if (status === 'Sedang Berjalan') el.addClass('bg-success');
      else if (status === 'Selesai') el.addClass('bg-danger');
      else el.addClass('bg-secondary');
      el.text(status);
    }

    function startCountdown(seconds) {
      clearInterval(timerInterval);
      let remaining = seconds;
      waktuMulai = new Date();
      waktuSelesai = new Date(waktuMulai.getTime() + remaining * 1000);
      $('#waktu-mulai').text(waktuMulai.toLocaleTimeString());
      $('#waktu-selesai').text(waktuSelesai.toLocaleTimeString());
      updateStatus('Sedang Berjalan');

      timerInterval = setInterval(() => {
        const mins = Math.floor(remaining / 60);
        const secs = remaining % 60;
        $('#timer-display').text(`${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`);
        if (remaining <= 0) {
          clearInterval(timerInterval);
          updateStatus('Selesai');
        }
        remaining--;
      }, 1000);
    }

    $('#start-timer').click(() => {
      const durasi = parseInt($('#durasi-input').val()) || 5;
      startCountdown(durasi * 60);
    });

    $('#stop-timer').click(() => {
      clearInterval(timerInterval);
      updateStatus('Menunggu');
    });

    $('#set-durasi').click(() => {
      const durasi = parseInt($('#durasi-input').val());
      if (isNaN(durasi) || durasi <= 0) return alert('Masukkan durasi valid!');
      startCountdown(durasi * 60);
    });

    function getDataSuhu() {
    $.get('{{ route('ruang-blanching.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {

    }, function(data, status) {
      if(data.status == true) {
        let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
        $('#suhu-rata-rata')[0].innerHTML = data.dataAvgSuhu;

        if (data.dataAvgSuhu > 25 && data.dataAvgSuhu < 30) {
          $('#status-suhu-ruangan')[0].innerHTML = 'Normal';
          classListSuhu.remove('text-success', 'text-warning', 'text-danger');
          classListSuhu.add('text-success');
        } else if (data.dataAvgSuhu > 30 && data.dataAvgSuhu < 50) {
          $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
          classListSuhu.remove('text-success', 'text-warning', 'text-danger');
          classListSuhu.add('text-warning');
        } else if (data.dataAvgSuhu >= 50 && data.dataAvgSuhu < 100) {
          $('#status-suhu-ruangan')[0].innerHTML = 'Bahaya';
          classListSuhu.remove('text-success', 'text-warning', 'text-danger');
          classListSuhu.add('text-danger');
        } else {
          $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
          classListSuhu.remove('text-success', 'text-warning', 'text-danger');
          classListSuhu.add('text-warning');
        }

          let suhuChart = new Chart(ctxSuhu, {
            type: 'line',
            data: {
              datasets: [{
                label: "Suhu (°C)",
                data: data.dataSuhu
              }],
              labels: data.dataWaktuSuhu
            },
            options: {
              responsive: true,
              scales: {
                y: { title: { display: true, text: 'Suhu (°C)', color: '#888' }, beginAtZero: true },
                x: { title: { display: true, text: 'Waktu', color: '#888' } }
              }
            }
          });

          if (ctxSuhu) {
            new Chart(ctxSuhu, {
              type: 'line',
              data: {
                labels: data.dataWaktuSuhu,
                datasets: [{ label: "Suhu (°C)", data: data.dataSuhu, fill: false, borderWidth: 2 }]
              },
              options: { responsive: true }
            });
          }
        }
      });
    }

    getDataSuhu();
  </script>
@endsection