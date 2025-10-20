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

      <!-- Filter -->
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
                  <label for="tanggal" class="form-label fw-semibold">Pilih Tanggal</label>
                  <input type="date" id="tanggal" name="tanggal" class="form-control w-100">

                  <label for="gudang" class="form-label fw-semibold mt-3">Pilih Gudang</label>
                  <select id="gudang" name="gudang" class="form-select w-100">
                    <option value="">-- Pilih Gudang --</option>
                    @foreach ($gudang as $item)
                      <option value="{{ $item->id_gudang }}">
                        {{ $item->nama_gudang }} ({{ $item->lokasi_gudang }})
                      </option>
                    @endforeach
                  </select>

                  <label for="ruangan" class="form-label fw-semibold mt-3">Pilih Ruang</label>
                  <select id="ruangan" name="ruangan" class="form-select w-100">
                    <option value="">-- Pilih Ruang --</option>
                    <!-- Ruangan akan diisi via AJAX -->
                  </select>

                  <small class="text-muted d-block mt-2 mb-3">
                    Tanggal dan Ruang yang dipilih akan menampilkan data 7 hari ke belakang.
                  </small>

                  <div class="d-flex gap-2">
                    <button type="button" class="btn px-4"
                      style="background-color:#A9DA2E; color: #fff; border-color: #A9DA2E;">Terapkan</button>
                    <button type="reset" class="btn btn-danger px-4">Reset</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Grafik dan Rata-rata -->
      <div class="row g-4">
        <!-- Chart Suhu -->
        <div class="col-xl-8 col-lg-7">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-body">
              <canvas id="chartSuhu" height="130"></canvas>
              <div class="mt-3">
                <h6 class="fw-semibold mb-1">Grafik Suhu</h6>
                <p class="text-muted mb-0 small">Total Suhu Ruang per Minggu</p>
                <hr class="dark-horizontal">
                <i class="bi bi-info-circle"> Data ini diambil dari range tanggal yang dipilih (ditampilkan per
                  minggu)</i>
              </div>
            </div>
          </div>
        </div>

        <!-- Suhu Ruangan -->
        <div class="col-xl-4 col-lg-5">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-body">
              <h6 class="fw-semibold mb-1">Suhu Ruangan</h6>
              <p class="text-muted small mb-3">Rata-rata suhu di ruang yang dipilih</p>
              <hr class="dark-horizontal">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="mb-1 fw-semibold">Rata-rata</p>
                  <p class="text-muted">Suhu Maksimum</p>
                </div>
                <div class="text-end">
                  <p class="mb-1 fw-semibold text-success" id="avgSuhu">-</p>
                  <p class="text-muted" id="maxSuhu">-</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Chart Kelembaban -->
        <div class="col-xl-8 col-lg-7">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-body">
              <canvas id="chartKelembaban" height="130"></canvas>
              <div class="mt-3">
                <h6 class="fw-semibold mb-1">Grafik Kelembaban</h6>
                <p class="text-muted mb-0 small">Total Kelembaban Ruang per Minggu</p>
                <hr class="dark-horizontal">
                <i class="bi bi-info-circle"> Data ini diambil dari range tanggal yang dipilih (ditampilkan per
                  minggu)</i>
              </div>
            </div>
          </div>
        </div>

        <!-- Kelembaban Ruangan -->
        <div class="col-xl-4 col-lg-5">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-body">
              <h6 class="fw-semibold mb-1">Kelembaban Ruangan</h6>
              <p class="text-muted small mb-3">Rata-rata kelembaban di ruang yang dipilih</p>
              <hr class="dark-horizontal">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="mb-1 fw-semibold">Rata-rata</p>
                  <p class="text-muted">Kelembaban Maksimum</p>
                </div>
                <div class="text-end">
                  <p class="mb-1 fw-semibold text-success" id="avgKelembaban">-</p>
                  <p class="text-muted" id="maxKelembaban">-</p>
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
    const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
    const ctxKelembaban = document.getElementById('chartKelembaban')?.getContext('2d');

    $(document).ready(function () {
      $('#gudang').change(function () {
        let gudangId = $(this).val();
        if (gudangId) {
          $.ajax({
            url: '/riwayat-data/get-ruangan/' + gudangId, // <-- diubah di sini
            type: 'GET',
            success: function (data) {
              $('#ruangan').empty();
              $('#ruangan').append('<option value="">-- Pilih Ruang --</option>');
              $.each(data, function (key, value) {
                $('#ruangan').append(
                  '<option value="' + value.id_ruangan + '">' + value.nama_ruangan + ' (' + value.tipe_ruangan + ')</option>'
                );
              });
            }
          });
        } else {
          $('#ruangan').empty();
          $('#ruangan').append('<option value="">-- Pilih Ruang --</option>');
        }
      });
    });
  </script>
@endsection
