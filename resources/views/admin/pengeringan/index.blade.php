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

      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Rekap Ruang Pengeringan</h5>
              <small class="text-muted">Pantauan Kondisi di Ruang Pengeringan Vanili</small>
            </div>
            <div class="card-body">
              <div class="row gy-4">
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
                      <span class="badge bg-light text-success px-3 py-2" id="status-suhu-ruangan">
                        Normal
                      </span>
                    </div>
                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0"><span id="suhu-rata-rata">-</span> °C</h2>
                    </div>
                    <div class="gudang-footer">
                      <small class="text-muted">
                        <i class="bi bi-cpu me-1"></i>Sensor: DHT22 (Suhu dan Kelembaban)
                      </small>
                    </div>
                  </div>
                </div>
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

      <div class="row mt-4">
        <div class="col-lg-12 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Grafik Suhu</h5>
                <small class="text-muted">Perubahan Suhu di Ruang Pengeringan</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartSuhu"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span
                    id="total-suhu">-</span> data
                  terakhir <span id="status-suhu"></span></small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Grafik Kelembaban</h5>
                <small class="text-muted">Perubahan Kelembaban di Ruang Pengeringan</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartKelembaban"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span
                    id="total-kelembaban">-</span> data
                  terakhir <span id="status-kelembaban"></span></small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Perbandingan Grafik Suhu dan Kelembaban</h5>
                <small class="text-muted">Perbandingan Suhu dan Kelembaban di Ruang Pengeringan</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartSuhuDanKelembaban"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span
                    id="total-suhu-dan-kelembaban">-</span> data
                  terakhir <span id="status-suhu-dan-kelembaban"></span></small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Kestabilan Suhu dalam Ruangan</h5>
                <small class="text-muted">Seberapa stabil suhu di ruang Pengeringan</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartStddevSuhu"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span
                    id="total-stddev-suhu">-</span> data
                  terakhir <span id="status-stddev-suhu"></span></small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Kestabilan Kelembaban dalam Ruangan</h5>
                <small class="text-muted">Seberapa stabil kelembaban di ruang Pengeringan</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartStddevKelembaban"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span
                    id="total-stddev-kelembaban">-</span> data
                  terakhir <span id="status-stddev-kelembaban"></span></small>
              </div>
            </div>
          </div>
        </div>
      </div>

    <div class="row mt-4">
  <div class="col-12">
    <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
      <div class="card-header bg-transparent border-0 position-relative text-center">
        <div>
          <h5 class="card-title mb-1 mt-2 fw-semibold">Daftar Blower</h5>
          <small class="text-muted">Indikator Operasional Blower (Total 2 Unit Aktif)</small>
        </div>
      </div>
      <div class="card-body">
        <div class="container">
          <div class="row mb-3 justify-content-center">
            @for ($i = 1; $i <= 2; $i++)
              <div class="col-md-4 mb-2">
                <div class="d-flex flex-column justify-content-between align-items-center py-3 px-2 border rounded-3 shadow-sm h-100"
                  style="background:#f8f9fa;">
                  <div class="d-flex flex-column align-items-center mb-3">
                    <i id="blower-{{ $i }}" class="bi bi-fan mb-2"
                      style="font-size: 2rem; color: gray;"></i>
                    <h6 class="mb-0 fw-semibold text-muted">
                      Blower {{ $i }}
                    </h6>
                  </div>
                  <div class="d-flex flex-column align-items-center">
                    <input class="form-check-input blower-switch mb-2" type="checkbox" id="switch-{{ $i }}"
                      data-id="{{ $i }}" data-sensor-id="" style="margin:0;">
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

        .blower-disabled {
          opacity: 0.7;
          cursor: not-allowed;
        }

        .blower-disabled .badge {
          font-size: 0.75rem;
          font-weight: 600;
        }

        .form-check-input:disabled {
          cursor: not-allowed;
          opacity: 0.5;
        }

        .blower-loading {
          position: relative;
          pointer-events: none;
          opacity: 0.6;
        }

        .blower-loading::after {
          content: '';
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 20px;
          height: 20px;
          border: 2px solid #f3f3f3;
          border-top: 2px solid #6EA017;
          border-radius: 50%;
          animation: spin-loader 1s linear infinite;
        }

        @keyframes spin-loader {
          from {
            transform: translate(-50%, -50%) rotate(0deg);
          }

          to {
            transform: translate(-50%, -50%) rotate(360deg);
          }
        }
      </style>
  </main>
@endsection

@section('script')
  <script>
    
    // Fungsi untuk load semua status blower dari database
    function loadAllBlowerStatus() {
      $.get('{{ route('ruang-pengeringan.getAllBlowersStatus', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}',
        function(response) {
          console.log('All Blowers Status:', response);
          if (response.status && response.data) {
            response.data.forEach(blower => {
              const blowerId = blower.blower_number;
              const isActive = blower.is_active;
              const switchEl = document.getElementById(`switch-${blowerId}`);
              if (switchEl) {
                switchEl.checked = isActive;
                switchEl.dataset.sensorId = blower.id_sensor;
                updateBlowerUI(blowerId, isActive);
                // console.log(`✓ Blower ${blowerId} loaded: ${isActive ? 'ON' : 'OFF'}`);
              }
            });
          }
        }
      ).fail(function(xhr) {
        console.error('Failed to load blowers status:', xhr.responseText);
        showNotification('error', 'Gagal memuat status blower');
      });
    }

    // Fungsi untuk load status blower individual (backup method)
    function loadBlowerStatus(blowerId, sensorId) {
      if (!sensorId || sensorId === 'undefined') {
        console.error(`Invalid sensor ID for Blower ${blowerId}`);
        return;
      }
      $.get(`/ruang-pengeringan/data/blower/${sensorId}`,
        function(response) {
          console.log(`Blower ${blowerId} Response:`, response);
          if (response.status && response.data) {
            const switchEl = document.getElementById(`switch-${blowerId}`);
            if (switchEl) {
              switchEl.checked = response.data.is_active;
              updateBlowerUI(blowerId, response.data.is_active);
              console.log(`✓ Blower ${blowerId}: ${response.data.is_active ? 'ON' : 'OFF'}`);
            }
          }
        }
      ).fail(function(xhr) {
        console.error(`Failed to load Blower ${blowerId}:`, xhr.responseText);
        const switchEl = document.getElementById(`switch-${blowerId}`);
        if (switchEl) {
          switchEl.checked = false;
          updateBlowerUI(blowerId, false);
        }
      });
    }

    // Fungsi untuk update status blower ke database
    function updateBlowerStatus(blowerId, newStatus, sensorId) {
      const switchEl = document.getElementById(`switch-${blowerId}`);
      const parentDiv = switchEl.closest('.d-flex');
      parentDiv.classList.add('blower-loading');
      switchEl.disabled = true;
      $.ajax({
        url: `/ruang-pengeringan/blower/${sensorId}/update`,
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          nilai_sensor: newStatus ? '1' : '0'
        },
        success: function(response) {
          parentDiv.classList.remove('blower-loading');
          switchEl.disabled = false;
          if (response.status) {
            console.log('✓ Blower updated:', response.msg);
            showNotification('success', response.msg);
            updateBlowerUI(blowerId, response.data.is_active);
          } else {
            console.error('✗ Update failed:', response.msg);
            showNotification('error', response.msg);
            switchEl.checked = !newStatus;
            updateBlowerUI(blowerId, !newStatus);
          }
        },
        error: function(xhr) {
          parentDiv.classList.remove('blower-loading');
          switchEl.disabled = false;
          console.error('✗ Error updating blower:', xhr.responseText);
          showNotification('error', 'Gagal mengupdate status blower');
          switchEl.checked = !newStatus;
          updateBlowerUI(blowerId, !newStatus);
        }
      });
    }

    // Fungsi untuk update UI blower
    function updateBlowerUI(blowerId, isActive) {
      const switchEl = document.getElementById(`switch-${blowerId}`);
      const label = switchEl?.nextElementSibling;
      const blowerIcon = document.getElementById(`blower-${blowerId}`);
      if (!switchEl || !label || !blowerIcon) return;
      if (isActive) {
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
    }

    // Fungsi notifikasi
    function showNotification(type, message) {
      const colors = {
        'success': '#28a745',
        'error': '#dc3545',
        'warning': '#ffc107'
      };
      const icons = {
        'success': '✓',
        'error': '✗',
        'warning': '⚠'
      };
      const bgColor = colors[type] || '#6c757d';
      const icon = icons[type] || 'ℹ';
      const textColor = type === 'warning' ? '#000' : '#fff';
      const notification = $(`
        <div style="position: fixed; top: 20px; right: 20px; z-index: 9999; 
                    background: ${bgColor}; color: ${textColor}; 
                    padding: 15px 20px; border-radius: 8px; 
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
                    animation: slideIn 0.3s ease-out; max-width: 400px;">
          <strong>${icon}</strong> ${message}
        </div>
      `);
      $('body').append(notification);
      setTimeout(() => {
        notification.css('animation', 'slideOut 0.3s ease-out');
        setTimeout(() => notification.remove(), 300);
      }, 3000);
    }

    // Event handler untuk setiap switch blower
    $(document).on('change', '.blower-switch', function() {
      const blowerId = $(this).data('id');
      const sensorId = $(this).data('sensor-id');
      const isChecked = $(this).is(':checked');
      console.log(`Blower ${blowerId} switched to: ${isChecked ? 'ON' : 'OFF'}`);
      updateBlowerStatus(blowerId, isChecked, sensorId);
    });

    //styling
    const styleBlower = document.createElement('style');
    styleBlower.innerHTML = `
      .bi-fan-spin {
        animation: fanSpin 1s linear infinite;
      }
      @keyframes fanSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
      @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
      }
      @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
      }
      .blower-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.6;
      }
      .blower-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #6EA017;
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }
      @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
      }
    `;
    document.head.appendChild(styleBlower);

    
    //inisialisasi grfaik
    let apexSuhu = null;
    let apexKelembaban = null;
    let apexSuhuDanKelembaban = null;
    let apexStddevSuhu = null;
    let apexStddevKelembaban = null;

    function initializeCharts() {
      let options = {
        chart: {
          type: 'line',
          height: '350px',
        },
        series: [{
          name: '?',
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
        noData: {
          text: 'Tidak ada data yang masuk untuk ditampilkan hari ini (periksa riwayat data untuk lebih lanjut)',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: '#888',
            fontSize: '16px',
            fontFamily: 'Helvetica'
          }
        }
      }
      let options2 = {
        chart: {
          type: 'line',
          height: '350px',
        },
        series: [{
          name: '?',
          data: []
        }],
        xaxis: {
          type: 'datetime',
          categories: []
        },
        stroke: {
          curve: 'smooth'
        },
        markers: {
          size: 5
        },
        noData: {
          text: 'Tidak ada data yang masuk untuk ditampilkan hari ini (periksa riwayat data untuk lebih lanjut)',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: '#888',
            fontSize: '16px',
            fontFamily: 'Helvetica'
          }
        }
      }
      apexSuhu = new ApexCharts($('#chartSuhu')[0], options);
      apexKelembaban = new ApexCharts($('#chartKelembaban')[0], options);
      apexSuhuDanKelembaban = new ApexCharts($('#chartSuhuDanKelembaban')[0], options);
      apexStddevSuhu = new ApexCharts($('#chartStddevSuhu')[0], options2);
      apexStddevKelembaban = new ApexCharts($('#chartStddevKelembaban')[0], options2);

      apexSuhu.render();
      apexKelembaban.render();
      apexSuhuDanKelembaban.render();
      apexStddevSuhu.render();
      apexStddevKelembaban.render();

      apexSuhu.updateSeries([]);
      apexKelembaban.updateSeries([]);
      apexSuhuDanKelembaban.updateSeries([]);
      apexStddevSuhu.updateSeries([]);
      apexStddevKelembaban.updateSeries([]);
    }

    function getDataSensor() {
      $.get('{{ route('ruang-pengeringan.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {}, function(data, status) {
        if (data.status == true) {
          let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
          let classListKelembaban = document.getElementById('status-kelembaban-ruangan').classList;
          
          apexSuhu.updateSeries([]);
          apexKelembaban.updateSeries([]);
          apexSuhuDanKelembaban.updateSeries([]);
          apexStddevSuhu.updateSeries([]);
          apexStddevKelembaban.updateSeries([]);

          apexStddevSuhu.updateOptions({
            yaxis: {
              title: {
                text: 'Std Dev Suhu'
              }
            },
            annotations: {
              yaxis: [{
                y: 1.0,
                borderColor: '#d40624',
                label: {
                  text: 'batas kestabilan suhu'
                }
              }, ],
            }
          });
          apexStddevKelembaban.updateOptions({
            yaxis: {
              title: {
                text: 'Std Dev Kelembaban'
              }
            },
            annotations: {
              yaxis: [{
                y: 5.0,
                borderColor: '#d40624',
                label: {
                  text: 'batas kestabilan kelembaban'
                }
              }, ],
            }
          });

          data.dataSensor.forEach(element => {
            console.log(element);
            $('#total-suhu').text(element.value.length);
            $('#total-kelembaban').text(element.value.length);
            $('#total-suhu-dan-kelembaban').text(element.value.length);
            $('#total-stddev-suhu').text(element.value.length);
            $('#total-stddev-kelembaban').text(element.value.length);

            if (element.flag_sensor == 'suhu_1') {
              let dataGrafik = [];
              data.dataWaktuSensor.forEach(elementWaktu => {
                if (elementWaktu.flag_sensor == 'suhu_1') {
                  elementWaktu.value.forEach(waktu => {
                    dataGrafik.push({
                      x: waktu,
                      y: element.value[dataGrafik.length]
                    });
                  });
                }
              });
              apexSuhu.appendSeries({
                name: 'Suhu 1 (°C)',
                data: dataGrafik
              });
              apexSuhuDanKelembaban.appendSeries({
                name: 'Suhu 1 (°C)',
                data: dataGrafik
              });
              apexStddevSuhu.appendSeries({
                name: "Suhu 1 (stddev)",
                data: element.stddev
              });
            } else if (element.flag_sensor == 'kelembaban_1') {
              let dataGrafik = [];
              data.dataWaktuSensor.forEach(elementWaktu => {
                if (elementWaktu.flag_sensor == 'kelembaban_1') {
                  elementWaktu.value.forEach(waktu => {
                    dataGrafik.push({
                      x: waktu,
                      y: element.value[dataGrafik.length]
                    });
                  });
                }
              });
              apexKelembaban.appendSeries({
                name: 'Kelembaban 1 (%)',
                data: dataGrafik
              });
              apexSuhuDanKelembaban.appendSeries({
                name: 'Kelembaban 1 (%)',
                data: dataGrafik
              });
              apexStddevKelembaban.appendSeries({
                name: "Kelembaban 1 (stddev)",
                data: element.stddev
              });
            } else if (element.flag_sensor == 'suhu_2') {
              let dataGrafik = [];
              data.dataWaktuSensor.forEach(elementWaktu => {
                if (elementWaktu.flag_sensor == 'suhu_2') {
                  elementWaktu.value.forEach(waktu => {
                    dataGrafik.push({
                      x: waktu,
                      y: element.value[dataGrafik.length]
                    });
                  });
                }
              });
              apexSuhu.appendSeries({
                name: 'Suhu 2 (°C)',
                data: dataGrafik
              });
              apexSuhuDanKelembaban.appendSeries({
                name: 'Suhu 2 (°C)',
                data: dataGrafik
              });
              apexStddevSuhu.appendSeries({
                name: "Suhu 2 (stddev)",
                data: element.stddev
              });
            } else if (element.flag_sensor == 'kelembaban_2') {
              let dataGrafik = [];
              data.dataWaktuSensor.forEach(elementWaktu => {
                if (elementWaktu.flag_sensor == 'kelembaban_2') {
                  elementWaktu.value.forEach(waktu => {
                    dataGrafik.push({
                      x: waktu,
                      y: element.value[dataGrafik.length]
                    });
                  });
                }
              });
              apexKelembaban.appendSeries({
                name: 'Kelembaban 2 (%)',
                data: dataGrafik
              });
              apexSuhuDanKelembaban.appendSeries({
                name: 'Kelembaban 2 (%)',
                data: dataGrafik
              });
              apexStddevKelembaban.appendSeries({
                name: "Kelembaban 2 (stddev)",
                data: element.stddev
              });
            }
          });

          $('#suhu-rata-rata')[0].innerHTML = data.currentSuhu;
          $('#kelembaban-rata-rata')[0].innerHTML = data.currentKelembaban;

          if (parseFloat(data.currentSuhu) > 20 && parseFloat(data.currentSuhu) < 30) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Normal';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-success');
          } else if (parseFloat(data.currentSuhu) > 30 && parseFloat(data.currentSuhu) < 50) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          } else if (parseFloat(data.currentSuhu) > 50 && parseFloat(data.currentSuhu) < 100) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Bahaya';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-danger');
          } else {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          }
          if (parseFloat(data.currentKelembaban) > 80) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Normal';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-success');
          } else if (parseFloat(data.currentKelembaban) > 60) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Peringatan';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-warning');
          } else if (parseFloat(data.currentKelembaban) > 0) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Bahaya';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          } else {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Kesalahan!';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          }
        }
      });
    }

    $(document).ready(function() {
      console.log('=== Initializing Blowers ===');
      loadAllBlowerStatus();
      setInterval(loadAllBlowerStatus, 1000);
      initializeCharts();
      setInterval(getDataSensor, 60000);
      getDataSensor();
      console.log('=== Initialization Complete ===');
    });
  </script>
@endsection