@extends('admin.layouts.main')

@section('title', 'Riwayat Data')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0 fw-semibold">Riwayat Data</h1>
          <p class="text-muted mb-0">Riwayat Data Ruang Vanili Agrofilia Permata</p>
        </div>
      </div>

      <!-- Filter -->
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2 fw-semibold">Filter Riwayat Data</h5>
              <small class="text-muted">Pilih tanggal untuk menampilkan data riwayat 1 hari.</small>
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
                    Tanggal dan Ruang yang dipilih akan menampilkan data 1 hari.
                  </small>

                  <div class="d-flex gap-2">
                    <button type="button" class="btn px-4" id="terapkan"
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
      <div class="row g-4" id="grafik-container">

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

      $('#terapkan').click(function() {
        let idRuangan = $('#ruangan').val();
        let tgl = $('#tanggal').val();
        $.get('{{ route('riwayat-data.blanching.getDataSensor', ['__ID__', '__TGL__']) }}'.replace('__ID__', idRuangan).replace('__TGL__', tgl), {

        }, function(data, status) {
          if(data.status == true) {
            let chartContainer = document.getElementById('grafik-container');
            let arrChart = [];
            chartContainer.innerHTML = '';
            data.dataSensor.forEach(element => {
              console.log(element);
              if(element.type == 'sensor') {
                let wrapper = document.createElement('div');
                wrapper.classList.add('col-xl-8', 'col-lg-7');
                wrapper.innerHTML += `
                  <!-- Chart ${element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + element.flag_sensor.split('_')[0].slice(1)} -->
                  <div class="card border-0 shadow-sm" style="border-radius: 18px;">
                    <div class="card-body">
                      <div class="mb-3">
                        <h6 class="fw-semibold mb-1">Grafik ${element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + element.flag_sensor.split('_')[0].slice(1)} ${element.flag_sensor.split('_')[1]} ${data.namaRuangan}</h6>
                        <p class="text-muted mb-0 small">Total ${element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + element.flag_sensor.split('_')[0].slice(1)} Ruang per hari yang dipilih</p>
                      </div>
                      <canvas id="${element.type}-${element.flag_sensor}" height="130"></canvas>
                      <div class="mt-3 text-muted">
                        <hr class="dark-horizontal">
                        <i class="bi bi-info-circle"> Data ini diambil dari tanggal yang dipilih (per hari)</i>
                      </div>
                    </div>
                  </div>
                `;

                let wrapper2 = document.createElement('div');
                wrapper2.classList.add('col-xl-4', 'col-lg-5');
                wrapper2.innerHTML += `
                  <!-- ${element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + element.flag_sensor.split('_')[0].slice(1)} Ruangan -->
                  <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
                    <div class="card-body">
                      <h6 class="fw-semibold mb-1">${element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + element.flag_sensor.split('_')[0].slice(1)} ${data.namaRuangan}</h6>
                      <p class="text-muted small mb-3">Laporan ${element.flag_sensor.split('_')[0]} di ruang yang dipilih</p>
                      <hr class="dark-horizontal">
                      <div class="row" id="report-${element.type}-${element.flag_sensor}"></div>
                    </div>
                  </div>
                `;

                chartContainer.appendChild(wrapper);
                chartContainer.appendChild(wrapper2);

                let arrDataLabel = element.value;
                let arrTimeLabel = element.time_label;
                let arrDataLabelChunks = [];
                let arrTimeLabelChunks = [];
                let chunkSize = 50;
                for (let i = 0; i < arrDataLabel.length; i += chunkSize) {
                  const chunk = arrDataLabel.slice(i, i + chunkSize);
                  arrDataLabelChunks.push(chunk);
                }
                for (let i = 0; i < arrTimeLabel.length; i += chunkSize) {
                  const chunk = arrTimeLabel.slice(i, i + chunkSize);
                  arrTimeLabelChunks.push(chunk);
                }

                // console.log(arrDataLabelChunks, arrTimeLabelChunks);
                let avgChunks = document.getElementById(`report-${element.type}-${element.flag_sensor}`);
                let i = 0;
                avgChunks.innerHTML = '';
                arrDataLabelChunks.forEach(element2 => {
                  const tempInt = element2.map(Number);
                  avgChunks.innerHTML += `
                    <div class="col-md-10">
                      <span>
                        Rata rata data diambil pada jam (${arrTimeLabelChunks[i][0]} - ${arrTimeLabelChunks[i][arrTimeLabelChunks[i].length - 1]}): 
                      </span>
                    </div>
                    <div class="col-md-2">
                      <span>
                         ${(tempInt.reduce((accumulator, currentValue) => accumulator + currentValue, 0) / tempInt.length).toString().slice(0, 4)}
                      </span>
                    </div>
                  `;
                  i++;
                });
                

                let labelText = '';

                if(element.flag_sensor.includes('suhu')) {
                  labelText = 'Suhu (°C)';
                } else if(element.flag_sensor.includes('kelembaban')) {
                  labelText = 'Kelembaban (%)';
                }

                arrChart.push(
                  new Chart(document.getElementById(`${element.type}-${element.flag_sensor}`)?.getContext('2d'), {
                    type: 'line',
                    data: {
                      datasets: [{
                        label: labelText,
                        data: element.value,
                        backgroundColor: '#C8F76A33',
                        borderColor: '#C8F76A',
                        pointBorderColor: '#0f172abf',
                        fill: true
                      }],
                      labels: element.time_label
                    },
                    options: {
                      responsive: true,
                      scales: {
                        y: { title: { display: true, text: 'Data ' + element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + element.flag_sensor.split('_')[0].slice(1), color: '#888' }, beginAtZero: true },
                        x: { title: { display: true, text: 'Waktu', color: '#888' } }
                      },
                      animation: {
                        duration: 800,
                      }
                    }
                  })
                );
              }
            });
          } else if(!data.status){
            Swal.fire({
              title: "Status",
              text: data.msg,
              icon: "error",
              allowOutsideClick: false,
              allowEscapeKey: false
            });
          }
        });
      });
    });
  </script>
@endsection
