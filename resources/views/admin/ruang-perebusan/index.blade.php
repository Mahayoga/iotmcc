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

      {{-- Rekap Kondisi Ruang Di Gudang --}}
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Pntauan Ruang Perebusan Vanili</h5>
              <small class="text-muted">Pantauan Kondisi di Setiap Ruang Gudang Vanili</small>
            </div>

            <div class="card-body">
              <div class="row gy-4">

                <!-- card Suhu -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-1">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-success">
                          <i class="bi bi-fire fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">Ruang Perebusan</h6>
                          <small class="text-muted">Kondisi suhu ruangan</small>
                        </div>
                      </div>
                      <span class="badge bg-light text-success px-3 py-2">Normal</span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0">78°C</h2>
                      <p class="text-dark small mb-2">Kelembapan: <strong>68%</strong></p>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-muted">
                        <i class="bi bi-cpu me-1"></i>Sensor: Suhu, Buzzer, LCD, ESP, LoRa
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
            <div class="card-body">
              <canvas id="chartSuhu" height="150"></canvas>
            </div>
          </div>
        </div>

        <!-- Grafik Kelembapan -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Grafik Kelembapan</h5>
              <small class="text-muted">Perubahan Kelembapan di Setiap Ruang Pada Gudang Vanili</small>
            </div>
            <div class="card-body">
              <canvas id="chartKelembapan" height="150"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

@endsection