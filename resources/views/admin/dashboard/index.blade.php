@extends('admin.layouts.main')

@section('title', 'Dashboard')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <!-- Page Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Dashboard</h1>
          <p class="text-muted mb-0">Rekap Gudang Vanili Agrofilia Permata</p>
        </div>
      </div>


      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Rekap Gudang</h5>
              <small class="text-muted">Pantauan kondisi dan perangkat di setiap gudang vanili</small>
            </div>

            <div class="card-body">
              <div class="row gy-4">

                <!-- Card Gudang 1 -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-1">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-success">
                          <i class="bi bi-fire fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">Gudang 1</h6>
                          <small class="text-muted">Ruang Perebusan</small>
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

                <!-- Card Gudang 2 -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-2">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-info">
                          <i class="bi bi-sun fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">Gudang 2</h6>
                          <small class="text-muted">Ruang Pengeringan</small>
                        </div>
                      </div>
                      <span class="badge bg-light text-success px-3 py-2">Normal</span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0">29°C</h2>
                      <p class="text-dark small mb-2">Kelembapan: <strong>70%</strong></p>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-muted">
                        <i class="bi bi-cpu me-1"></i>Sensor: DHT, ESP, LoRa, Step Down
                      </small>
                    </div>
                  </div>
                </div>

                <!-- Card Gudang 3 -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-3">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-warning">
                          <i class="bi bi-box-seam fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h6 class="mb-0 fw-bold">Gudang 3</h6>
                          <small class="text-light">Ruang Penyimpanan</small>
                        </div>
                      </div>
                      <span class="badge bg-light text-warning px-3 py-2">Perlu Cek</span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0">31°C</h2>
                      <p class="text-light small mb-2">Kelembapan: <strong>75%</strong></p>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-light">
                        <i class="bi bi-cpu me-1"></i>Sensor: DHT, ESP, LoRa, Step Down
                      </small>
                    </div>
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