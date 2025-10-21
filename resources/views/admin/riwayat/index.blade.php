@extends('admin.layouts.main')

@section('title', 'Riwayat Data')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0 fw-semibold">Riwayat Data</h1>
          <p class="text-muted mb-0">Riwayat Data Ruang Pengolahan Vanili Agrofilia Permata</p>
        </div>
      </div>

      <!-- Filter -->
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius:18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2 fw-semibold">Filter Riwayat Data</h5>
              <small class="text-muted">Pilih tanggal untuk menampilkan data riwayat 7 hari ke belakang.</small>
            </div>
            <div class="card-body">
              <div class="row gy-3">
                <div class="col-12">
                  <label for="tanggal" class="form-label fw-semibold">Pilih Tanggal</label>
                  <input type="date" id="tanggal" class="form-control w-100">

                  <label for="gudang" class="form-label fw-semibold mt-3">Pilih Gudang</label>
                  <select id="gudang" class="form-select w-100">
                    <option value="">-- Pilih Gudang --</option>
                    @foreach ($gudang as $item)
                      <option value="{{ $item->id_gudang }}">{{ $item->nama_gudang }}</option>
                    @endforeach
                  </select>

                  <label for="ruangan" class="form-label fw-semibold mt-3">Pilih Ruang</label>
                  <select id="ruangan" class="form-select w-100">
                    <option value="">-- Pilih Ruang --</option>
                    <option value="perebusan">Ruang Perebusan (Blanching)</option>
                    <option value="fermentasi">Ruang Fermentasi</option>
                    <option value="pengeringan">Ruang Pengeringan</option>
                  </select>

                  <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn px-4" id="terapkan"
                      style="background-color:#A9DA2E; color:#fff;">Terapkan</button>
                    <button type="button" class="btn btn-danger px-4" id="resetBtn">Reset</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bagian Grafik dan Rata-rata -->
      <div id="dataContainer" style="display:none;">
        <div class="row g-4">
          <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius:18px;">
              <div class="card-body">
                <canvas id="chartSuhu"></canvas>
                <div class="mt-3">
                  <h6 class="fw-semibold mb-1" id="judulGrafikSuhu">Grafik Suhu</h6>
                  <p class="text-muted mb-0 small">Total Suhu Ruang per Minggu</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
              <div class="card-body">
                <h6 class="fw-semibold mb-1" id="judulSuhuRuangan">Suhu Ruangan</h6>
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

          <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius:18px;">
              <div class="card-body">
                <canvas id="chartKelembaban"></canvas>
                <div class="mt-3">
                  <h6 class="fw-semibold mb-1" id="judulGrafikKelembaban">Grafik Kelembaban</h6>
                  <p class="text-muted mb-0 small">Total Kelembaban Ruang per Minggu</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
              <div class="card-body">
                <h6 class="fw-semibold mb-1" id="judulKelembabanRuangan">Kelembaban Ruangan</h6>
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

    </div>
  </main>
@endsection

@section('script')
<script>
  const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
  const ctxKelembaban = document.getElementById('chartKelembaban')?.getContext('2d');

  let chartSuhuInstance = null;
  let chartKelembabanInstance = null;

  $(document).ready(function () {

    //ajax gudabg
    $('#gudang').change(function () {
      let gudangId = $(this).val();
      if (gudangId) {
        $.ajax({
          url: '/riwayat-data/get-ruangan/' + gudangId,
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

    //Terapkan
    $('#terapkan').click(function() {
      let idRuangan = $('#ruangan').val();
      if (!idRuangan) {
        alert('Silakan pilih ruang terlebih dahulu!');
        return;
      }

      $('#dataContainer').show();

      const dummyData = {
        perebusan: { suhu: [30, 32, 31, 29, 33, 34, 30], kelembaban: [70, 68, 72, 69, 73, 74, 70] },
        fermentasi: { suhu: [35, 34, 36, 37, 38, 36, 35], kelembaban: [60, 62, 63, 61, 64, 65, 62] },
        pengeringan: { suhu: [45, 44, 46, 47, 48, 45, 46], kelembaban: [40, 41, 39, 38, 42, 43, 41] }
      };

      const ruang = idRuangan.includes('perebusan') ? 'perebusan' :
                    idRuangan.includes('fermentasi') ? 'fermentasi' : 'pengeringan';
      const data = dummyData[ruang];

      if (chartSuhuInstance) chartSuhuInstance.destroy();
      if (chartKelembabanInstance) chartKelembabanInstance.destroy();

      function renderChart(ctx, label, data, color) {
        return new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
              label: label,
              data: data,
              borderColor: color,
              backgroundColor: color + '33',
              tension: 0.3,
              fill: true
            }]
          },
          options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
          }
        });
      }

      chartSuhuInstance = renderChart(ctxSuhu, 'Suhu Ruang', data.suhu, '#ff6384');
      chartKelembabanInstance = renderChart(ctxKelembaban, 'Kelembaban Ruang', data.kelembaban, '#36a2eb');

      const avg = arr => (arr.reduce((a,b)=>a+b,0) / arr.length).toFixed(1);
      $('#avgSuhu').text(avg(data.suhu));
      $('#maxSuhu').text(Math.max(...data.suhu));
      $('#avgKelembaban').text(avg(data.kelembaban));
      $('#maxKelembaban').text(Math.max(...data.kelembaban));

      $('#judulGrafikSuhu').text('Grafik Suhu - ' + ruang.charAt(0).toUpperCase() + ruang.slice(1));
      $('#judulGrafikKelembaban').text('Grafik Kelembaban - ' + ruang.charAt(0).toUpperCase() + ruang.slice(1));
      $('#judulSuhuRuangan').text('Suhu Ruangan ' + ruang.charAt(0).toUpperCase() + ruang.slice(1));
      $('#judulKelembabanRuangan').text('Kelembaban Ruangan ' + ruang.charAt(0).toUpperCase() + ruang.slice(1));

      $.get('{{ route('riwayat-data.blanching.getDataSensor', ['__ID__']) }}'.replace('__ID__', idRuangan), {}, function(data, status) {
        if (data.status == true) console.log('Data backend:', data);
      });
    });

    //Reset
    $('#resetBtn').click(function() {
      $('#tanggal').val('');
      $('#gudang').val('');
      $('#ruangan').empty().append('<option value="">-- Pilih Ruang --</option>');
      $('#dataContainer').hide();
      $('#avgSuhu, #maxSuhu, #avgKelembaban, #maxKelembaban').text('-');

      if (chartSuhuInstance) { chartSuhuInstance.destroy(); chartSuhuInstance = null; }
      if (chartKelembabanInstance) { chartKelembabanInstance.destroy(); chartKelembabanInstance = null; }

      console.log('Filter dan grafik telah direset');
    });

  });
</script>
@endsection
