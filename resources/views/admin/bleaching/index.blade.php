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
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Grafik Suhu Alat Bleaching</h5>
                <small class="text-muted">Perubahan suhu dalam 11 data terakhir</small>
              </div>
              <button type="button" class="btn btn-secondary" onclick="resetZoomChart()">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Zoom
              </button>
            </div>

            <div class="card-body">
              <canvas id="chartSuhu" height="150"></canvas>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan 11 data terakhir</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Timer dan informasi proses -->
        <div class="row g-4 mb-4">
          <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
              <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-1 mt-2">Timer Bleaching</h5>
                <small class="text-muted">Hitung mundur proses Bleaching dari database</small>
              </div>
              <div class="card-body text-center">
                <h1 id="timer-display" class="fw-bold display-3 text-danger mb-0">00:00</h1>
                <small class="text-muted d-block mt-2">Waktu tersisa</small>
              </div>
            </div>
          </div>

          <!-- Informasi Proses -->
          <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
              <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-1 mt-2">Informasi Proses</h5>
                <small class="text-muted">Status proses bleaching otomatis</small>
              </div>
              <div class="card-body d-flex flex-column justify-content-center">
                <p><b>Waktu Mulai:</b> <span id="waktu-mulai">-</span></p>
                <p><b>Perkiraan Selesai:</b> <span id="waktu-selesai">-</span></p>
                <p><b>Status:</b> <span id="status-proses" class="badge bg-secondary">Menunggu</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>

  </main>
@endsection

@section('script')
  
  <script>

    function resetZoomChart() {
      suhuChart.resetZoom();
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
          y: { title: { display: true, text: 'Suhu (°C)', color: '#888' }, beginAtZero: true },
          x: { title: { display: true, text: 'Waktu', color: '#888' } }
        },
        animation: {
          duration: 800,
        },
        plugins: {
          zoom: {
            zoom: {
              wheel: {
                enabled: true,
              },
              pinch: {
                enabled: true
              },
              drag: {
                enabled: true
              },
              mode: 'xy',
            }
          }
        }
      }
    });

    function getDataSensor() {
      $.get('{{ route('alat-bleaching.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {

      }, function (data, status) {
        let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
        let suhuTotal = 0;
        let totalDataSuhu = 0;
        data.dataSensor.forEach(sensor => {
          if (sensor.flag_sensor.includes('suhu')) {
            sensor.value.forEach(v => {
              suhuTotal += parseFloat(v);
              totalDataSuhu++;
            });
          }
        });

        let rataRataSuhu = (suhuTotal / totalDataSuhu).toFixed(2);
        $('#suhu-rata-rata').text(rataRataSuhu);

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

        let suhuData = data.dataSensor.find(e => e.flag_sensor === 'suhu_1');
        let waktuData = data.dataWaktuSensor.find(e => e.flag_sensor === 'suhu_1');

        if (suhuData && waktuData) {
          suhuChart.data.labels = waktuData.value.reverse();
          suhuChart.data.datasets[0].data = suhuData.value.reverse().map(v => parseFloat(v));
          suhuChart.update();
        }
      });
    }

    function getDataTimer() {
      $.get('{{ route('alat-bleaching.getDataTimer', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', function (res) {
        if (res.status && res.dataTimer.length > 0) {
          let timer = res.dataTimer[0];
          const nilai = parseInt(timer.nilai_timer);
          const limit = parseInt(timer.limit_timer);
          const sisa = Math.max(limit - nilai, 0);
          const m = Math.floor(sisa / 60).toString().padStart(2, '0');
          const s = (sisa % 60).toString().padStart(2, '0');
          $('#timer-display').text(`${m}:${s}`);

          if (sisa <= 0) {
            $('#status-proses')
              .text('Selesai ✅')
              .removeClass()
              .addClass('badge bg-success');
          } else {
            $('#status-proses')
              .text('Berlangsung ⏳')
              .removeClass()
              .addClass('badge bg-warning text-dark');
          }

          if (timer.updated_at) {
            const waktuMulai = new Date(timer.updated_at);
            const waktuSelesai = new Date(waktuMulai.getTime() + limit * 1000);
            $('#waktu-mulai').text(waktuMulai.toLocaleTimeString());
            $('#waktu-selesai').text(waktuSelesai.toLocaleTimeString());
          }
        }
      }).fail(function (xhr, status, error) {
        console.error('Gagal mengambil data timer:', error);
      });
    }

    setInterval(getDataSensor, 1000);
  </script>
@endsection