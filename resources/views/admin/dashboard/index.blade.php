@extends('admin.layouts.main')

@section('title', 'Dashboard')

@section('content')


  {{-- Logika otomatis untuk status ruang gudang belum selesai --}}



  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <!-- Page Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Dashboard</h1>
          <p class="text-muted mb-0">Rekap Gudang Vanili Agrofilia Permata</p>
        </div>
      </div>

      {{-- Rekap Kondisi Ruang Di Gudang --}}
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Rekap Ruang Gudang</h5>
              <small class="text-muted">Pantauan Kondisi di Setiap Ruang Gudang Vanili</small>
            </div>

            <div class="card-body">
              <div class="row gy-4">

                <!-- Card Ruang 1 -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-1">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-success">
                          <i class="bi bi-fire fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">{{ $dataRuangan[0]['nama_ruangan'] ?? 'Ruang 1' }}</h6>
                        </div>
                      </div>
                      <span class="badge bg-light text-success px-3 py-2">
                        {{ ($dataRuangan[0]['suhu'] ?? 0) <= 30 ? 'Normal' : 'Perlu Cek' }}
                      </span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0">{{ $dataRuangan[0]['suhu'] ?? '-' }}°C</h2>
                      <p class="text-dark small mb-2">Kelembapan:
                        <strong>{{ $dataRuangan[0]['kelembapan'] ?? '-' }}%</strong>
                      </p>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-muted">
                        <i class="bi bi-cpu me-1"></i>Sensor: Suhu, Buzzer, LCD, ESP, LoRa
                      </small>
                    </div>
                  </div>
                </div>

                <!-- Card Ruang 2 -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-2">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-info">
                          <i class="bi bi-sun fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">{{ $dataRuangan[1]['nama_ruangan'] ?? 'Ruang 2' }}</h6>
                        </div>
                      </div>
                      <span class="badge bg-light text-success px-3 py-2">
                        {{ ($dataRuangan[1]['suhu'] ?? 0) <= 30 ? 'Normal' : 'Perlu Cek' }}
                      </span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0">{{ $dataRuangan[1]['suhu'] ?? '-' }}°C</h2>
                      <p class="text-dark small mb-2">Kelembapan:
                        <strong>{{ $dataRuangan[1]['kelembapan'] ?? '-' }}%</strong>
                      </p>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-muted">
                        <i class="bi bi-cpu me-1"></i>Sensor: DHT, ESP, LoRa
                      </small>
                    </div>
                  </div>
                </div>

                <!-- Card Ruang 3 -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-3">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-warning">
                          <i class="bi bi-box-seam fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">{{ $dataRuangan[2]['nama_ruangan'] ?? 'Ruang 3' }}</h6>
                        </div>
                      </div>
                      <span class="badge bg-light text-warning px-3 py-2">
                        {{ ($dataRuangan[2]['suhu'] ?? 0) <= 30 ? 'Normal' : 'Perlu Cek' }}
                      </span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0">{{ $dataRuangan[2]['suhu'] ?? '-' }}°C</h2>
                      <p class="text-dark small mb-2">Kelembapan:
                        <strong>{{ $dataRuangan[2]['kelembapan'] ?? '-' }}%</strong>
                      </p>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-dark">
                        <i class="bi bi-cpu me-1"></i>Sensor: DHT, ESP, LoRa
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
              <small class="text-muted">Perubahan Suhu di Setiap Ruang Pada Gudang Vanili</small>
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
              <h5 class="card-title mb-1 mt-2">Grafik Kelembapan</h5>
              <small class="text-muted">Perubahan Kelembapan di Setiap Ruang Pada Gudang
                Vanili</small>
            </div>
            <div class="card-body" style="height: 350px;">
              <canvas id="chartKelembapan" style="width:100%; height:100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  {{--
  <pre style="background:#f8f9fa;padding:10px;border-radius:8px;">
    {{ json_encode($grafikSuhu, JSON_PRETTY_PRINT) }}
    </pre>
  <pre style="background:#f8f9fa;padding:10px;border-radius:8px;">
    {{ json_encode($grafikKelembapan, JSON_PRETTY_PRINT) }}
    </pre> --}}


    <script>

      // grafik monitoring suhu dan kelembapan
      document.addEventListener('DOMContentLoaded', function () {
        const grafikSuhu = @json($grafikSuhu ?? []);
        const grafikKelembapan = @json($grafikKelembapan ?? []);

        console.log("grafikSuhu:", grafikSuhu);
        console.log("grafikKelembapan:", grafikKelembapan);

        // grafik suhu
        const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
        if (ctxSuhu && Object.keys(grafikSuhu).length > 0) {
          const suhuDatasets = [];

          Object.keys(grafikSuhu).forEach((namaRuangan, index) => {
            const dataRuangan = grafikSuhu[namaRuangan];

            suhuDatasets.push({
              label: namaRuangan,
              data: dataRuangan.map(item => item.nilai),
              borderColor: ['#00A86B', '#FFD93D', '#FF6B6B'][index] || '#888',
              backgroundColor: ['rgba(0,168,107,0.1)', 'rgba(255,217,61,0.1)', 'rgba(255,107,107,0.1)'][index] || 'rgba(136,136,136,0.1)',
              tension: 0.4,
              borderWidth: 3
            });
          });

          new Chart(ctxSuhu, {
            type: 'line',
            data: {
              labels: grafikSuhu[Object.keys(grafikSuhu)[0]]?.map(item => item.waktu) ?? [],
              datasets: suhuDatasets
            },
            options: {
              responsive: true,
              scales: {
                y: { title: { display: true, text: 'Suhu (°C)', color: '#888' }, beginAtZero: true },
                x: { title: { display: true, text: 'Waktu', color: '#888' } }
              }
            }
          });
        }

        // grafik kelembapan
        const ctxKelembapan = document.getElementById('chartKelembapan')?.getContext('2d');
        if (ctxKelembapan && Object.keys(grafikKelembapan).length > 0) {
          const kelembapanDatasets = [];

          Object.keys(grafikKelembapan).forEach((namaRuangan, index) => {
            const dataRuangan = grafikKelembapan[namaRuangan];

            kelembapanDatasets.push({
              label: namaRuangan,
              data: dataRuangan.map(item => item.nilai),
              borderColor: ['#00A86B', '#FFD93D', '#FF6B6B'][index] || '#888',
              backgroundColor: ['rgba(0,168,107,0.1)', 'rgba(255,217,61,0.1)', 'rgba(255,107,107,0.1)'][index] || 'rgba(136,136,136,0.1)',
              tension: 0.4,
              borderWidth: 3
            });
          });

          new Chart(ctxKelembapan, {
            type: 'line',
            data: {
              labels: grafikKelembapan[Object.keys(grafikKelembapan)[0]]?.map(item => item.waktu) ?? [],
              datasets: kelembapanDatasets
            },
            options: {
              responsive: true,
              scales: {
                y: { title: { display: true, text: 'Kelembapan (%)', color: '#888' }, beginAtZero: true },
                x: { title: { display: true, text: 'Waktu', color: '#888' } }
              }
            }
          });
        }
      });

    </script>


@endsection