@extends('admin.layouts.main')

@section('title', 'Dashboard')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Dashboard</h1>
          <p class="text-muted mb-0">Rekap Gudang Vanili Agrofilia Permata</p>
        </div>
      </div>

      {{-- rekap ruang gudang --}}
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Rekap Ruang Gudang</h5>
              <small class="text-muted">Pantauan Kondisi di Setiap Ruang Gudang Vanili</small>
            </div>

            {{-- card bleaching --}}
            <div class="card-body">
              <div class="row gy-4">
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-1">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-success">
                          <i class="bi bi-fire fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">{{ $dataRuangan[1]['nama_ruangan'] }}</h6>
                        </div>
                      </div>
                      <span class="badge px-3 py-2 bg-white border text-{{ $dataRuangan[1]['status_color'] }}">
                        {{ $dataRuangan[1]['status_icon'] }} {{ $dataRuangan[1]['status'] }}
                      </span>
                    </div>
                    <div class="gudang-main mt-3">
                      @if($dataRuangan[1]['suhu_bleaching'])
                        <h2 class="fw-bold mb-0">{{ $dataRuangan[1]['suhu_bleaching'] }}°C</h2>
                        <p class="text-dark small mb-2"><strong>Suhu Terakhir (07:00 - 10:00)</strong></p>
                      @else
                        <h2 class="fw-bold mb-0 text-muted">-</h2>
                        <p class="text-dark small mb-2"><strong>Suhu Terakhir (07:00 - 10:00)</strong></p>
                        <p class="text-muted small mb-0"><i>Belum ada data hari ini</i></p>
                      @endif
                    </div>
                  </div>
                </div>

                {{-- card fermentasi --}}
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-2">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-info">
                          <i class="bi bi-sun fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">{{ $dataRuangan[2]['nama_ruangan'] }}</h6>
                        </div>
                      </div>
                      <span class="badge px-3 py-2 bg-white border text-{{ $dataRuangan[2]['status_color'] }}">
                        {{ $dataRuangan[2]['status_icon'] }} {{ $dataRuangan[2]['status'] }}
                      </span>
                    </div>
                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0">{{ $dataRuangan[2]['suhu'] }}°C</h2>
                      <p class="text-dark small mb-2">
                        Kelembapan: <strong>{{ $dataRuangan[2]['kelembapan'] }}%</strong>
                      </p>
                    </div>
                  </div>
                </div>
                
                {{-- card pengeringan --}}
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-3">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-warning">
                          <i class="bi bi-fan fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">{{ $dataRuangan[3]['nama_ruangan'] }}</h6>
                        </div>
                      </div>
                      <span class="badge px-3 py-2 bg-white border text-{{ $dataRuangan[3]['status_color'] }}">
                        {{ $dataRuangan[3]['status_icon'] }} {{ $dataRuangan[3]['status'] }}
                      </span>
                    </div>
                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0">{{ $dataRuangan[3]['suhu'] }}°C</h2>
                      <p class="text-dark small mb-2">
                        Kelembapan: <strong>{{ $dataRuangan[3]['kelembapan'] }}%</strong>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- bagian grafik --}}
      <div class="row mt-4">

        {{-- grafik bleaching --}}
        <div class="col-12 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Suhu Alat Bleaching</h5>
              <small class="text-muted">Perubahan Suhu Alat (Jam 7-10 Pagi dengan Interval 5 Menit)</small>
            </div>
            <div class="card-body" style="height: 350px;">
              <div id="chartBleaching" style="width:100%; height:100%;"></div>
            </div>
          </div>
        </div>

        {{-- grafik suhu --}}
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Suhu Ruangan</h5>
              <small class="text-muted">Fermentasi & Pengeringan</small>
            </div>
            <div class="card-body" style="height: 300px;">
              <div id="chartSuhu" style="width:100%; height:100%;"></div>
            </div>
          </div>
        </div>

        {{-- grafik kelembaban --}}
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Kelembapan</h5>
              <small class="text-muted">Fermentasi & Pengeringan</small>
            </div>
            <div class="card-body" style="height: 300px;">
              <div id="chartKelembapan" style="width:100%; height:100%;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  {{-- apexcharts --}}
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const grafikSuhu = @json($grafikSuhu ?? []);
      const grafikKelembapan = @json($grafikKelembapan ?? []);
      const grafikBleaching = @json($grafikBleaching ?? []);

      // grafik bleaching
      if (Object.keys(grafikBleaching).length > 0) {
        const labels = grafikBleaching[Object.keys(grafikBleaching)[0]].map(i => i.waktu);
        const series = Object.entries(grafikBleaching).map(([nama, data]) => ({
          name: nama, data: data.map(i => i.nilai)
        }));

        new ApexCharts(document.querySelector("#chartBleaching"), {
          chart: { type: 'line', height: 330, zoom: { enabled: true, type: 'x', autoScaleYaxis: true }, toolbar: { show: true } },
          stroke: { curve: 'monotoneCubic', width: 3 },
          markers: { size: 4, strokeColors: '#fff', strokeWidth: 2, hover: { size: 6 } },
          series: series,
          xaxis: { categories: labels, labels: { rotate: -45 } },
          yaxis: { title: { text: 'Suhu (°C)' } },
          colors: ['#00A86B', '#FF6B6B', '#FFD93D'],
          legend: { position: 'top' }
        }).render();
      }

      // grafik suhu
      if (Object.keys(grafikSuhu).length > 0) {
        const labels = grafikSuhu[Object.keys(grafikSuhu)[0]].map(i => i.waktu);
        const series = Object.entries(grafikSuhu).map(([nama, data]) => ({
          name: nama, data: data.map(i => i.nilai)
        }));

        new ApexCharts(document.querySelector("#chartSuhu"), {
          chart: { type: 'line', height: 280, zoom: { enabled: true, type: 'x', autoScaleYaxis: true }, toolbar: { show: true } },
          stroke: { curve: 'monotoneCubic', width: 3 },
          markers: { size: 4, strokeColors: '#fff', strokeWidth: 2, hover: { size: 6 } },
          series: series,
          xaxis: { categories: labels, labels: { rotate: -45 } },
          yaxis: { title: { text: 'Suhu (°C)' } },
          colors: ['#FF9800', '#4CAF50', '#03A9F4'],
          legend: { position: 'top' }
        }).render();
      }

      // grafik kelembapan
      if (Object.keys(grafikKelembapan).length > 0) {
        const labels = grafikKelembapan[Object.keys(grafikKelembapan)[0]].map(i => i.waktu);
        const series = Object.entries(grafikKelembapan).map(([nama, data]) => ({
          name: nama, data: data.map(i => i.nilai)
        }));

        new ApexCharts(document.querySelector("#chartKelembapan"), {
          chart: { type: 'line', height: 280, zoom: { enabled: true, type: 'x', autoScaleYaxis: true }, toolbar: { show: true } },
          stroke: { curve: 'monotoneCubic', width: 3 },
          markers: { size: 4, strokeColors: '#fff', strokeWidth: 2, hover: { size: 6 } },
          series: series,
          xaxis: { categories: labels, labels: { rotate: -45 } },
          yaxis: { title: { text: 'Kelembapan (%)' } },
          colors: ['#03A9F4', '#8BC34A', '#FFC107'],
          legend: { position: 'top' }
        }).render();
      }
    });
  </script>
@endsection
