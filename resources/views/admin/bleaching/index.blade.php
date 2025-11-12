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
                <small class="text-muted">Perubahan suhu dalam alat bleaching</small>
              </div>
            </div>

            <div class="card-body">
              <div id="chartSuhu"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah <span id="total-suhu">-</span> data terakhir</small>
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
      </div>
    </div>
  </main>
@endsection

@section('script')
  <script>
    let apexSuhu = null;

    function initializeCharts() {
      let options = {
        chart: {
          type: 'line',
          height: '350px',
        },
        series: [{
          name: 'Suhu (°C)',
          data: []
        }],
        xaxis: {
          categories: []
        },
        stroke: {
          curve: 'smooth'
        },
        markers: {
          size: 5
        },
      }
      apexSuhu = new ApexCharts($('#chartSuhu')[0], options);
      apexSuhu.render();
    }

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
          let suhuValues = suhuData.value.map(v => parseFloat(v));
          
          apexSuhu.updateOptions({
            series: [{
              name: 'Suhu (°C)',
              data: suhuValues
            }],
            xaxis: {
              categories: waktuData.value
            }
          });

          $('#total-suhu').text(suhuValues.length);
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
          
          updateDisplay(sisa);

          if (limit > 0) {
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
              $('#waktu-mulai').text(waktuMulai.toLocaleTimeString('id-ID'));
              $('#waktu-selesai').text(waktuSelesai.toLocaleTimeString('id-ID'));
            }
          } else {
            $('#status-proses')
              .text('Menunggu')
              .removeClass()
              .addClass('badge bg-secondary');
            $('#waktu-mulai').text('-');
            $('#waktu-selesai').text('-');
          }
        } else {
          updateDisplay(0);
          $('#waktu-mulai').text('-');
          $('#waktu-selesai').text('-');
          $('#status-proses')
            .text('Menunggu')
            .removeClass()
            .addClass('badge bg-secondary');
        }
      }).fail(function (xhr, status, error) {
        console.error('Gagal mengambil data timer:', error);
      });
    }

    function updateDisplay(seconds) {
      const m = Math.floor(seconds / 60).toString().padStart(2, '0');
      const s = (seconds % 60).toString().padStart(2, '0');
      $('#timer-display').text(`${m}:${s}`);
    }

    $('#set-timer-btn').on('click', function () {
      const durasiMenit = parseInt($('#durasi-input').val());
      
      if (isNaN(durasiMenit) || durasiMenit <= 0) {
        alert('Masukkan durasi yang valid (lebih dari 0 menit)');
        return;
      }

      $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');

      $.ajax({
        url: '{{ route('alat-bleaching.setLimitTimer', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}',
        method: 'POST',
        data: {
          limit_timer: durasiMenit,
          flag_sensor: 'timer_1', 
          _token: '{{ csrf_token() }}'
        },
        success: function (response) {
          if (response.status) {
            alert('Timer berhasil diset! Timer akan berjalan otomatis.');
            $('#durasi-input').val('');
            
            getDataTimer();
          } else {
            alert('Gagal set timer: ' + response.message);
          }
        },
        error: function (xhr, status, error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat set timer');
        },
        complete: function () {
          $('#set-timer-btn').prop('disabled', false).html('<i class="bi bi-clock-fill"></i> Set Timer');
        }
      });
    });

    initializeCharts();
    getDataSensor();
    getDataTimer();
    setInterval(getDataSensor, 1000);
    setInterval(getDataTimer, 1000);
    
  </script>
@endsection