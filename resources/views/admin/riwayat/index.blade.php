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
      <button type="button" class="btn btn-sm btn-secondary mb-4" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="bi bi-funnel"></i> Atur Filter
      </button>

      <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Atur Filter Riwayat Data</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="reset" id="resetBtn" class="btn btn-danger px-4" data-bs-dismiss="modal">Reset</button>
              <button type="button" class="btn px-4" data-bs-dismiss="modal" id="terapkan" style="background-color:#A9DA2E; color: #fff; border-color: #A9DA2E;">Terapkan</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Grafik dan Rata-rata -->
      <div class="row g-4" id="grafik-container">
        <div class="col-md-12 p-2 bg-white rounded text-center">
          <i class="fs-5 text-muted ">Silahkan filter data terlebih dahulu</i>
        </div>
      </div>
    </div>
  </main>
@endsection

@section('script')
  <script>
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
        Swal.fire({
          title: 'Status',
          text: 'Loading data, mohon tunggu...',
          icon: 'info',
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false
        });
        let idRuangan = $('#ruangan').val();
        let tgl = $('#tanggal').val();
        $.get('{{ route('riwayat-data.blanching.getDataSensor', ['__ID__', '__TGL__']) }}'.replace('__ID__', idRuangan).replace('__TGL__', tgl), {

        }, function(data, status) {
          try {
            if(data.status == true) {
              let chartContainer = document.getElementById('grafik-container');
              let arrChart = [];
              chartContainer.innerHTML = '';
              data.dataSensor.forEach(element => {
                if(element.type == 'sensor') {
                  let wrapper = document.createElement('div');
                  wrapper.classList.add('col-xl-8', 'col-lg-7');
                  wrapper.innerHTML += `
                    <!-- Chart ${element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + " " + element.flag_sensor.split('_')[0].slice(1)} -->
                    <div class="card border-0 shadow-sm" style="border-radius: 18px;">
                      <div class="card-body">
                        <div class="mb-3">
                          <h6 class="fw-semibold mb-1">Grafik ${element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + element.flag_sensor.split('_')[0].slice(1)} ${element.flag_sensor.split('_')[1]} ${data.namaRuangan}</h6>
                          <p class="text-muted mb-0 small">Total ${element.flag_sensor.split('_')[0].charAt(0).toUpperCase() + element.flag_sensor.split('_')[0].slice(1)} Ruang per hari yang dipilih</p>
                        </div>
                        <div id="${element.type}-${element.flag_sensor}"></div>
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

                  let options = {
                    chart: {
                      type: 'line',
                      height: '350px',
                    },
                    series: [{
                      name: '?',
                      data: []
                    }],
                    xaxis: {
                      categories: []
                    },
                    stroke: {
                      curve: 'smooth'
                    },
                    markers: {
                      size: 2
                    },
                  }
                  let idChart = "#" + element.type + "-" + element.flag_sensor;
                  let tempApex = new ApexCharts($(idChart)[0], options);
                  tempApex.render();
                  tempApex.updateSeries([]);
                  tempApex.updateOptions({
                    xaxis: {
                      categories: element.time_label
                    }
                  });
                  tempApex.appendSeries({
                    name: labelText,
                    data: element.value
                  });
                }
              });
              Swal.fire({
                title: 'Status',
                text: 'Filter berhasil!',
                icon: 'success',
                showConfirmButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false
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
          } catch(err) {
            Swal.fire({
              title: "Status",
              text: err,
              icon: "error",
              allowOutsideClick: false,
              allowEscapeKey: false
            });
          }
        });
      });
    
      $('#resetBtn').click(function() {
        $('#grafik-container').html(`
          <div class="col-md-12 p-2 bg-white rounded text-center">
            <i class="fs-5 text-muted ">Silahkan filter data terlebih dahulu</i>
          </div>
        `);
        $('#gudang').val('');
        $('#ruangan').html('<option value="">-- Pilih Ruang --</option>');
        $('#tanggal').val('');
        Swal.fire({
          title: 'Status',
          text: 'Reset berhasil!',
          icon: 'success',
        });
      });
    });
  </script>
@endsection
