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

      <!-- Suhu Rata-rata Card -->
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
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
      </div>

      <!-- Timer dan Informasi Proses-->
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
              <button id="start-stop-timer-btn" class="btn btn-success btn-sm mt-3">
                <i class="bi bi-play-fill"></i> <span id="start-stop-text-btn">Mulai Timer</span>
              </button>
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
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <p><b>Waktu Mulai:</b> <span id="waktu-mulai">-</span></p>
                  <p><b>Perkiraan Selesai:</b> <span id="waktu-selesai">-</span></p>
                  <p><b>Status:</b> <span id="status-proses" class="badge bg-secondary">Menunggu</span></p>
                </div>
                <div class="col-md-6">
                  <label for="durasi-input" class="form-label mb-2 fw-bold">Durasi Bleaching (menit)</label>
                  <div class="input-group">
                    <input type="number" id="durasi-input" class="form-control" placeholder="Masukkan durasi" min="1">
                    <button id="set-timer-btn" class="btn btn-primary">
                      <i class="bi bi-clock-fill"></i> Set Timer
                    </button>
                  </div>
                  <small class="text-muted d-block mt-1">Timer akan berjalan otomatis dari database</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Grafik Suhu -->
      <div class="row mt-4">
        <div class="col-lg-12 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Grafik Suhu Alat Bleaching</h5>
                <small class="text-muted">Perubahan Suhu di Alat Bleaching (Sensor 1 & 2)</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartSuhu"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span
                    id="total-suhu">-</span> data terakhir</small>
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
    
    // Inisialisasi grafik
    let apexSuhu = null;
    let timerStatus = null;

    function initializeCharts() {
      let optionsSuhu = {
        chart: {
          type: 'line',
          height: '350px',
        },
        series: [],
        xaxis: {
          categories: []
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        markers: {
          size: 5
        },
        dataLabels: {
          enabled: false
        },
        colors: ['#00E396', '#008FFB'],
        legend: { 
          position: 'bottom',
          horizontalAlign: 'center',
          offsetY: 0,
          fontSize: '14px',
          fontWeight: 500,
          markers: {
            width: 12,
            height: 12,
            radius: 12,
          },
          itemMargin: {
            horizontal: 15,
            vertical: 5
          }
        },
        tooltip: {
          shared: true,
          intersect: false,
        }
      };

      apexSuhu = new ApexCharts($('#chartSuhu')[0], optionsSuhu);
      apexSuhu.render();
      apexSuhu.updateSeries([]);
    }

    function getDataSensor() {
      $.get('{{ route('alat-bleaching.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {
      }, function (data, status) {
        if (data.status == true) {
          let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
          apexSuhu.updateSeries([]);
          
          $('#suhu-rata-rata').text(data.rataRataSuhu);
          let rataRataSuhu = parseFloat(data.rataRataSuhu);
          
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

          if (data.dataWaktuSensor.length > 0) {
            apexSuhu.updateOptions({
              xaxis: {
                categories: data.dataWaktuSensor[0].value
              }
            });
          }

          data.dataSensor.forEach(element => {
            $('#total-suhu').text(element.value.length);

            if (element.flag_sensor == 'suhu_1') {
              apexSuhu.appendSeries({
                name: 'Suhu 1 (°C)',
                data: element.value.map(v => parseFloat(v))
              });
            } else if (element.flag_sensor == 'suhu_2') {
              apexSuhu.appendSeries({
                name: 'Suhu 2 (°C)',
                data: element.value.map(v => parseFloat(v))
              });
            }
          });
        }
      });
    }

    function getDataTimer() {
      $.get('{{ route('alat-bleaching.getDataTimer', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', function (res) {
        // console.log(res);
        if(res.status) {
          let data = res.dataTimer[0];
          updateDisplay(parseInt(data.sisa_timer));
          if(data.flag_timer == 'start') {
            let startTime = new Date(parseInt(data.nilai_timer));
            let stopTime = new Date(parseInt(data.nilai_timer) + (parseInt(data.limit_timer) * 1000));
            $('#waktu-mulai').text(startTime.toLocaleTimeString('id-ID'));
            $('#waktu-selesai').text(stopTime.toLocaleTimeString('id-ID'));
            $('#status-proses').text('Masih Berlangsung!').removeClass().addClass('badge bg-warning text-dark');
            $('#start-stop-text-btn').text('Stop Timer');
          } else if(data.flag_timer == 'stop') {
            $('#waktu-mulai').text('-');
            $('#waktu-selesai').text('-');
            $('#status-proses').text('Selesai!').removeClass().addClass('badge bg-success');
            $('#start-stop-text-btn').text('Mulai Timer');
          }
        }
      });
    }

    function updateDisplay(seconds) {
      const m = Math.floor(seconds / 60).toString().padStart(2, '0');
      const s = (seconds % 60).toString().padStart(2, '0');
      $('#timer-display').text(`${m}:${s}`);
    }

    $('#start-stop-timer-btn').on('click', function() {
      $.post('{{ route('alat-bleaching.startStopLimitTimer', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}',
      {
        _token: '{{ csrf_token() }}'
      },function(data) {
        console.log()
        if() {

        }
      });
    });
    
    $('#set-timer-btn').on('click', function () {
      
    });

    initializeCharts();
    getDataSensor();
    getDataTimer();
    setInterval(getDataSensor, 60000);
    setInterval(getDataTimer, 1000);

  </script>
@endsection