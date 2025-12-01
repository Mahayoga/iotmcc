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

      {{-- grafik menampilkan data terbaru --}}
      <div class="row mt-4">

        {{-- grafik bleaching --}}
        <div class="col-12 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Suhu Alat Bleaching</h5>
              <small class="text-muted">Perubahan Suhu Alat (Jam 7-10 Pagi dengan Interval 5 Menit)</small>
            </div>
            <div class="card-body">
              <div id="chartBleaching"></div>
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
            <div class="card-body">
              <div id="chartSuhu"></div>
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
            <div class="card-body">
              <div id="chartKelembapan"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- grafik trend 14 hari --}}
      <div class="row mt-4">
        <div class="col-12 mb-3">
          <h4 class="mb-0">Trend 14 Hari Terakhir</h4>
          <p class="text-muted small mb-0">Rata-rata harian suhu dan kelembapan per ruangan</p>
        </div>

        {{-- Bleaching --}}
        <div class="col-12 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">
                <i class="bi bi-fire text-danger me-2"></i>
                Trend Suhu {{ $trendBleaching['nama_ruangan'] ?? 'Bleaching' }}
              </h5>
              <small class="text-muted">Rata-rata suhu harian (07:00 - 10:00 WIB)</small>
            </div>
            <div class="card-body" style="min-height: 360px;">
              <div id="trendBleaching"></div>
            </div>
          </div>
        </div>

        {{-- Fermentasi --}}
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">
                <i class="bi bi-thermometer-half text-warning me-2"></i>
                Trend Suhu {{ $trendFermentasiSuhu['nama_ruangan'] ?? 'Fermentasi' }}
              </h5>
              <small class="text-muted">Rata-rata suhu harian</small>
            </div>
            <div class="card-body" style="min-height: 320px;">
              <div id="trendFermentasiSuhu"></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">
                <i class="bi bi-droplet-half text-primary me-2"></i>
                Trend Kelembapan {{ $trendFermentasiKelembapan['nama_ruangan'] ?? 'Fermentasi' }}
              </h5>
              <small class="text-muted">Rata-rata kelembapan harian</small>
            </div>
            <div class="card-body" style="min-height: 320px;">
              <div id="trendFermentasiKelembapan"></div>
            </div>
          </div>
        </div>

        {{-- Pengeringan --}}
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">
                <i class="bi bi-thermometer-half text-warning me-2"></i>
                Trend Suhu {{ $trendPengeringanSuhu['nama_ruangan'] ?? 'Pengeringan' }}
              </h5>
              <small class="text-muted">Rata-rata suhu harian</small>
            </div>
            <div class="card-body" style="min-height: 320px;">
              <div id="trendPengeringanSuhu"></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">
                <i class="bi bi-droplet-half text-primary me-2"></i>
                Trend Kelembapan {{ $trendPengeringanKelembapan['nama_ruangan'] ?? 'Pengeringan' }}
              </h5>
              <small class="text-muted">Rata-rata kelembapan harian</small>
            </div>
            <div class="card-body" style="min-height: 320px;">
              <div id="trendPengeringanKelembapan"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const grafikSuhu = @json($grafikSuhu ?? []);
      const grafikKelembapan = @json($grafikKelembapan ?? []);
      const grafikBleaching = @json($grafikBleaching ?? []);
      const trendBleaching = @json($trendBleaching ?? []);
      const trendFermentasiSuhu = @json($trendFermentasiSuhu ?? []);
      const trendFermentasiKelembapan = @json($trendFermentasiKelembapan ?? []);
      const trendPengeringanSuhu = @json($trendPengeringanSuhu ?? []);
      const trendPengeringanKelembapan = @json($trendPengeringanKelembapan ?? []);

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

      // bart chart bleaching
      function renderTrendLineChart(elementId, dataObj, yAxisTitle, unit = '°C', color = '#3B82F6', minY = null, maxY = null) {
        const container = document.querySelector(elementId);

        if (!dataObj || !dataObj.data || dataObj.data.length === 0) {
          container.innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-info-circle me-2"></i>Belum ada data</div>';
          return;
        }

        const categories = dataObj.data.map(d => d.tanggal);
        const values = dataObj.data.map(d => d.nilai);

        let calculatedMin = minY;
        let calculatedMax = maxY;

        if (!minY || !maxY) {
          const dataMin = Math.min(...values);
          const dataMax = Math.max(...values);
          const range = dataMax - dataMin;
          const padding = range * 0.2;

          calculatedMin = Math.floor(dataMin - padding);
          calculatedMax = Math.ceil(dataMax + padding);
        }

        const options = {
          chart: {
            type: 'line',
            height: elementId === '#trendBleaching' ? 340 : 300,
            toolbar: {
              show: true,
              tools: {
                download: true,
                selection: true,
                zoom: true,
                zoomin: true,
                zoomout: true,
                pan: true,
                reset: true
              }
            },
            animations: {
              enabled: true,
              easing: 'easeinout',
              speed: 800
            },
            zoom: {
              enabled: true,
              type: 'x',
              autoScaleYaxis: true
            }
          },
          series: [{
            name: yAxisTitle,
            data: values
          }],
          stroke: {
            curve: 'MonotoneCubic',
            width: 3,
            colors: [color]
          },
          markers: {
            size: 5,
            strokeWidth: 2,
            strokeColors: '#fff',
            fillColors: color,
            hover: {
              size: 7
            }
          },
          colors: [color],
          dataLabels: {
            enabled: true,
            formatter: function (val) {
              return val.toFixed(1) + unit;
            },
            offsetY: -5,
            background: {
              enabled: false,
            },
            style: {
              fontSize: '11px',
              fontWeight: 'bold',
              colors: [color]
            }
          },
          xaxis: {
            categories: categories,
            labels: {
              style: {
                fontSize: '11px',
                fontWeight: 500
              },
              rotate: -45,
              rotateAlways: categories.length > 7
            },
            axisBorder: {
              show: true
            },
            axisTicks: {
              show: true
            },
            tooltip: {
              enabled: false
            }
          },
          yaxis: {
            title: {
              text: yAxisTitle,
              style: {
                fontSize: '12px',
                fontWeight: 600
              }
            },
            min: calculatedMin,
            max: calculatedMax,
            labels: {
              formatter: function (val) {
                return val ? val.toFixed(1) + unit : '';
              }
            }
          },
          grid: {
            borderColor: '#e7e7e7',
            strokeDashArray: 4,
            padding: {
              top: 20,
              right: 10,
              bottom: 10,
              left: 10
            },
            xaxis: {
              lines: {
                show: true
              }
            },
            yaxis: {
              lines: {
                show: true
              }
            }
          },
        };

        new ApexCharts(container, options).render();
      }

      renderTrendLineChart(
        '#trendBleaching',
        trendBleaching,
        'Suhu',
        '°C',
        '#EF4444',
        null,
        null
      );


      renderTrendLineChart(
        '#trendFermentasiSuhu',
        trendFermentasiSuhu,
        'Suhu',
        '°C',
        '#F59E0B',
        null,
        null
      );

      renderTrendLineChart(
        '#trendFermentasiKelembapan',
        trendFermentasiKelembapan,
        'Kelembapan',
        '%',
        '#3B82F6',
        0,
        100
      );

      renderTrendLineChart(
        '#trendPengeringanSuhu',
        trendPengeringanSuhu,
        'Suhu',
        '°C',
        '#F59E0B',
        null,
        null
      );

      renderTrendLineChart(
        '#trendPengeringanKelembapan',
        trendPengeringanKelembapan,
        'Kelembapan',
        '%',
        '#3B82F6',
        0,
        100
      );
    });
  </script>
@endsection