@extends('admin.layouts.main')

@section('title', 'Ruang Perebusan')

@section('content')

  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0 fw-semibold">Riwayat Data</h1>
          <p class="text-muted mb-0">Riwayat Data Ruang Pengeboran Vanili Agrofilia Permata</p>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2 fw-semibold">Filter Riwayat Data</h5>
              <small class="text-muted">Pilih tanggal untuk menampilkan data riwayat 7 hari ke belakang.</small>
            </div>

            <div class="card-body">
              <div class="row gy-3">
                <div class="col-12">
                  <label for="tanggal" class="form-label fw-semibold">Pilih Tanggal Spesifik</label>
                  <input type="date" id="tanggal" name="tanggal" class="form-control w-100">

                  <label for="ruang" class="form-label fw-semibold mt-3">Pilih Ruang Spesifik</label>
                  <select id="ruang" name="ruang" class="form-select w-100">
                    <option value="">-- Pilih Ruang --</option>
                    <option value="bleaching">Ruang Bleaching</option>
                    <option value="fermentasi">Ruang Fermentasi</option>
                    <option value="pengeringan">Ruang Pengeringan</option>
                  </select>

                  <small class="text-muted d-block mt-2 mb-3">
                    Tanggal dan Ruang yang dipilih akan menampilkan data 7 hari ke belakang.
                  </small>

                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success px-4">Terapkan</button>
                    <button type="reset" class="btn btn-outline-secondary px-4">Reset</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-xl-8 col-lg-7">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-body">
              <canvas id="chartSuhu" height="130"></canvas>
              <div class="mt-3">
                <h6 class="fw-semibold mb-1">Grafik Suhu</h6>
                <p class="text-muted mb-0 small">Total Suhu Ruang per Minggu</p>
                <hr class="dark-horizontal">
                <i class="bi bi-info-circle"> Data ini di ambil dari range tanggal yang dipilih (ditampilkan per minggu)</i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 col-lg-5">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-body">
              <h6 class="fw-semibold mb-1">Suhu Ruangan</h6>
              <p class="text-muted small mb-3">Rata-rata suhu di ruang perebusan</p>
              <hr class="dark-horizontal">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="mb-1 fw-semibold">Rata-rata</p>
                  <p class="text-muted">Suhu Maksimum</p>
                </div>
                <div class="text-end">
                  <p class="mb-1 fw-semibold text-success">32°C</p>
                  <p class="text-muted">36°C</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-8 col-lg-7">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-body">
              <canvas id="chartKelembaban" height="130"></canvas>
              <div class="mt-3">
                <h6 class="fw-semibold mb-1">Grafik Kelembaban</h6>
                <p class="text-muted mb-0 small">Total Kelembaban Ruang per Minggu</p>
                <hr class="dark-horizontal">
                 <i class="bi bi-info-circle"> Data ini di ambil dari range tanggal yang dipilih (ditampilkan per minggu)</i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 col-lg-5">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-body">
              <h6 class="fw-semibold mb-1">Kelembaban Ruangan</h6>
              <p class="text-muted small mb-3">Rata-rata kelembaban di ruang perebusan</p>
              <hr class="dark-horizontal">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="mb-1 fw-semibold">Rata-rata</p>
                  <p class="text-muted">Kelembaban Maksimum</p>
                </div>
                <div class="text-end">
                  <p class="mb-1 fw-semibold text-success">68%</p>
                  <p class="text-muted">79%</p>
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

</script>
@endsection