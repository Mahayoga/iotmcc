@extends('admin.layouts.main')

@section('title', 'Ruang Perebusan')

@section('content')
<main class="admin-main">
  <div class="container-fluid p-4 p-lg-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Ruang Perebusan</h1>
        <p class="text-muted mb-0">Rekap Ruang Perebusan Vanili Agrofilia Permata</p>
      </div>
    </div>

    <!-- Rekap Ruang Perebusan -->
    <div class="row g-4 mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 18px;">
          <div class="card-header bg-transparent border-0">
            <h5 class="card-title mb-1 mt-2">Rekap Ruang Perebusan</h5>
            <small class="text-muted">Pantauan Kondisi di Ruang Perebusan Vanili</small>
          </div>

          <div class="card-body">
            <div class="row mb-4">
              <!-- Grafik Suhu -->
              <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm p-4" style="border-radius:18px; background:#ffffff;">
                  <h5 class="fw-bold mb-3 text-dark">Grafik Suhu Real-time</h5>
                  <canvas id="chartSuhu" height="180"></canvas>
                </div>
              </div>

              <!-- Timer Digital -->
              <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm p-4 text-center" style="border-radius:18px; background:#ffffff;">
                  <h5 class="fw-bold mb-3 text-dark">Timer Digital</h5>
                  <h1 id="timerDisplay" class="display-3 fw-bold text-success mb-3">
                    {{ $timer ?? '00' }} s
                  </h1>
                  <div class="mt-3 d-flex justify-content-center gap-3">
                    <button id="btnStart" class="btn btn-success px-4">
                      <i class="bi bi-play-fill me-1"></i>Mulai
                    </button>
                    <button id="btnStop" class="btn btn-danger px-4">
                      <i class="bi bi-stop-fill me-1"></i>Hentikan
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="card border-0 shadow-sm p-4" style="border-radius:18px; background:#ffffff;">
              <h6 class="fw-bold text-dark mb-3">Informasi Tambahan</h6>
              <p class="mb-1 text-muted">
                Waktu mulai: 
                <strong id="startTime" class="text-dark">
                  {{ $startTime ? \Carbon\Carbon::parse($startTime)->format('H:i:s d/m/Y') : '-' }}
                </strong>
              </p>
              <p class="mb-1 text-muted">
                Perkiraan selesai: 
                <strong id="endTime" class="text-dark">
                  {{ $endTime ? \Carbon\Carbon::parse($endTime)->format('H:i:s d/m/Y') : '-' }}
                </strong>
              </p>
              <p class="mb-0 text-muted">
                Status: 
                <span id="status" 
                  class="badge 
                    @if($status === 'Sedang Berjalan') bg-success 
                    @elseif($status === 'Selesai') bg-danger 
                    @elseif($status === 'Dihentikan') bg-warning 
                    @else bg-secondary @endif">
                  {{ $status ?? 'Menunggu' }}
                </span>
              </p>
            </div>

          </div> <!-- /card-body -->
        </div> <!-- /card -->
      </div>
    </div>
  </div>
</main>

{{-- ======================= Chart & Timer Script ======================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@php
    // Mapping data waktu di PHP sebelum dikirim ke JS
    $waktuData = $dataSuhu->pluck('created_at')->map(function($t) {
        return \Carbon\Carbon::parse($t)->format('H:i:s');
    });
    $suhuData = $dataSuhu->pluck('nilai_sensor');
@endphp

<script>
  // Data dari database (controller)
  const suhuData = @json($suhuData);
  const waktuData = @json($waktuData);

  // Inisialisasi Chart.js
  const ctx = document.getElementById('chartSuhu').getContext('2d');
  const suhuChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: waktuData.reverse(),
      datasets: [{
        label: 'Suhu (°C)',
        data: suhuData.reverse(),
        borderWidth: 2,
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(255, 99, 132, 0.1)',
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      animation: false,
      scales: {
        y: { 
          beginAtZero: true,
          ticks: { callback: val => val + '°C' }
        }
      },
      plugins: {
        legend: { labels: { color: '#495057' } }
      }
    }
  });

  // Timer Digital (sinkron dengan data DB)
  let timerValue = {{ $timer ?? 0 }};
  let interval;
  const timerDisplay = document.getElementById('timerDisplay');
  const statusLabel = document.getElementById('status');

  document.getElementById('btnStart').addEventListener('click', () => {
    clearInterval(interval);
    statusLabel.className = 'badge bg-success';
    statusLabel.textContent = 'Sedang Berjalan';
    interval = setInterval(() => {
      timerValue--;
      timerDisplay.innerText = timerValue + ' s';
      if (timerValue <= 0) {
        clearInterval(interval);
        statusLabel.className = 'badge bg-danger';
        statusLabel.textContent = 'Selesai';
      }
    }, 1000);
  });

  document.getElementById('btnStop').addEventListener('click', () => {
    clearInterval(interval);
    statusLabel.className = 'badge bg-warning';
    statusLabel.textContent = 'Dihentikan';
  });
</script>
@endsection
