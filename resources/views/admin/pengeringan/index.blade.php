@extends('admin.layouts.main')

@section('title', 'Ruang Pengeringan')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <!-- Page Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Ruang Pengeringan</h1>
          <p class="text-muted mb-0">Rekap Ruang Pengeringan Vanili Agrofilia Permata</p>
        </div>
      </div>
      <!-- Rekap Ruang Pengeringan -->
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Rekap Ruang Pengeringan</h5>
              <small class="text-muted">Pantauan Kondisi di Ruang Pengeringan Vanili</small>
            </div>
            <div class="card-body">
              <div class="row gy-4">

                <!-- Card Ruang 1 -->
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

                <!-- Card Ruang 2 -->
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
                      <span class="badge bg-light text-success px-3 py-2" id="status-kelembaban-ruangan">
                        Normal
                      </span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0"><span id="kelembaban-rata-rata">-</span> %</h2>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-muted">
                        <i class="bi bi-cpu me-1"></i>Sensor: DHT22 (Suhu dan Kelembaban)
                      </small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Grafik Monitoring Suhu dan Kelembapan --}}
      <div class="row mt-4">
        <!-- Grafik Suhu -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Suhu</h5>
              <small class="text-muted">Perubahan Suhu di Ruang Fermentasi</small>
            </div>
            <div class="card-body" style="height: 350px;">
              <canvas id="chartSuhu" style="width:100%; height:100%;"></canvas>
            </div>
          </div>
        </div>

        <!-- Grafik Kelembapan -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Kelembaban</h5>
              <small class="text-muted">Perubahan Kelembaban di Ruang Fermentasi</small>
            </div>
            <div class="card-body" style="height: 350px;">
              <canvas id="chartKelembaban" style="width:100%; height:100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@section('script')
  <script>
    const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
    const ctxKelembaban = document.getElementById('chartKelembaban')?.getContext('2d');

    function getDataSuhu() {
      $.get('{{ route('ruang-fermentasi.getDataSuhu', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {

      }, function(data, status) {
        if(data.status == true) {
          let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
          let classListKelembaban = document.getElementById('status-kelembaban-ruangan').classList;
          $('#suhu-rata-rata')[0].innerHTML = data.dataAvgSuhu;
          $('#kelembaban-rata-rata')[0].innerHTML = data.dataAvgKelembaban;

          if(data.dataAvgSuhu > 25 && data.dataAvgSuhu < 30) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Normal';
            classListSuhu.remove('text-success');
            classListSuhu.remove('text-warning');
            classListSuhu.remove('text-danger');
            classListSuhu.add('text-success');
          } else if(data.dataAvgSuhu > 30 && data.dataAvgSuhu < 50) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success');
            classListSuhu.remove('text-warning');
            classListSuhu.remove('text-danger');
            classListSuhu.add('text-warning');
          } else if(data.dataAvgSuhu > 50 && data.dataAvgSuhu < 100) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Bahaya';
            classListSuhu.remove('text-success');
            classListSuhu.remove('text-warning');
            classListSuhu.remove('text-danger');
            classListSuhu.add('text-danger');
          } else {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success');
            classListSuhu.remove('text-warning');
            classListSuhu.remove('text-danger');
            classListSuhu.add('text-warning');
          }

          if(data.dataAvgKelembaban > 80) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Normal';
            classListKelembaban.remove('text-success');
            classListKelembaban.remove('text-warning');
            classListKelembaban.remove('text-danger');
            classListKelembaban.add('text-success');
          } else if(data.dataAvgKelembaban > 60) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Peringatan';
            classListKelembaban.remove('text-success');
            classListKelembaban.remove('text-warning');
            classListKelembaban.remove('text-danger');
            classListKelembaban.add('text-warning');
          } else if(data.dataAvgKelembaban > 0) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Bahaya';
            classListKelembaban.remove('text-success');
            classListKelembaban.remove('text-warning');
            classListKelembaban.remove('text-danger');
            classListKelembaban.add('text-danger');
          } else {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Kesalahan!';
            classListKelembaban.remove('text-success');
            classListKelembaban.remove('text-warning');
            classListKelembaban.remove('text-danger');
            classListKelembaban.add('text-danger');
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

          let kelembabanChart = new Chart(ctxKelembaban, {
            type: 'line',
            data: {
              datasets: [{
                label: "Kelembaban (%)",
                data: data.dataKelembaban
              }],
              labels: data.dataWaktuKelembaban
            },
            options: {
              responsive: true,
              scales: {
                y: { title: { display: true, text: 'Kelembaban (%)', color: '#888' }, beginAtZero: true },
                x: { title: { display: true, text: 'Waktu', color: '#888' } }
              }
            }
          });
        }
      });
    }
    getDataSuhu();
  </script>
@endsection