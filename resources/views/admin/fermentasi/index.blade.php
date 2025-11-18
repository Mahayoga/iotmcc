@extends('admin.layouts.main')

@section('title', 'Dashboard')

@section('content')
  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <!-- Page Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Ruangan Fermentasi</h1>
          <p class="text-muted mb-0">Rekap Ruang Fermentasi Vanili Agrofilia Permata</p>
        </div>
      </div>

      {{-- Rekap Kondisi Ruang Di Gudang --}}
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Rekap Ruang Fermentasi</h5>
              <small class="text-muted">Pantauan Kondisi di Ruang Fermentasi</small>
            </div>

            <div class="card-body">
              <div class="row gy-4">

                <!-- Card Ruang 1 -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-1">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-success">
                          <i class="bi bi-thermometer-half fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h5 class="mb-0 fw-bold">Suhu rata - rata</h5>
                        </div>
                      </div>
                      <span class="badge bg-light text-success px-3 py-2" id="status-suhu-ruangan">
                        Normal
                      </span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0"><span id="suhu-rata-rata">-</span> °C</h2>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-muted">
                        <i class="bi bi-cpu me-1"></i>Sensor: DHT22 (Suhu dan Kelembaban)
                      </small>
                    </div>
                  </div>
                </div>

                <!-- Card Ruang 2 -->
                <div class="col-xl-4 col-md-6">
                  <div class="gudang-box gudang-1">
                    <div class="gudang-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="gudang-icon bg-white text-success">
                          <i class="bi bi-moisture fs-4"></i>
                        </div>
                        <div class="ms-2">
                          <h5 class="mb-0 fw-bold">Kelembaban rata - rata</h5>
                        </div>
                      </div>
                      <span class="badge bg-light text-success px-3 py-2" id="status-kelembaban-ruangan">
                        Normal
                      </span>
                    </div>

                    <div class="gudang-main mt-3">
                      <h2 class="fw-bold mb-0"><span id="kelembaban-rata-rata">-</span> %</h2>
                    </div>

                    <div class="gudang-footer">
                      <small class="text-muted">
                        <i class="bi bi-cpu me-1"></i>Sensor: DHT22 (Suhu dan Kelembaban)
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
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Grafik Suhu</h5>
                <small class="text-muted">Perubahan Suhu di Ruang Fermentasi</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartSuhu"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah <span id="total-suhu">-</span> data
                  terakhir</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Grafik Kelembapan -->
        <div class="col-lg-6 mb-4">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Grafik Kelembaban</h5>
                <small class="text-muted">Perubahan Kelembaban di Ruang Fermentasi</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartKelembaban"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah <span id="total-kelembaban">-</span> data
                  terakhir</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Perbandingan Grafik Suhu dan Kelembaban</h5>
                <small class="text-muted">Perbandingan Suhu dan Kelembaban di Ruang Fermentasi</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartSuhuDanKelembaban"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah <span id="total-suhu-dan-kelembaban">-</span> data
                  terakhir</small>
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
    // const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
    // const ctxKelembaban = document.getElementById('chartKelembaban')?.getContext('2d');
    // const ctxSuhuDanKelembaban = document.getElementById('chartSuhuDanKelembaban')?.getContext('2d');
    let apexSuhu = null;
    let apexKelembaban = null;
    let apexSuhuDanKelembaban = null;

    function initializeCharts() {
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
          size: 5
        },
      }
      apexSuhu = new ApexCharts($('#chartSuhu')[0], options);
      apexKelembaban = new ApexCharts($('#chartKelembaban')[0], options);
      apexSuhuDanKelembaban = new ApexCharts($('#chartSuhuDanKelembaban')[0], options);

      apexSuhu.render();
      apexKelembaban.render();
      apexSuhuDanKelembaban.render();
    }

    function getDataSensor() {
      $.get('{{ route('ruang-fermentasi.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {

      }, function (data, status) {
        if (data.status == true) {
          let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
          let classListKelembaban = document.getElementById('status-kelembaban-ruangan').classList;

          let suhuTotal = 0;
          let totalDataSuhu = 0;
          let kelTotal = 0;
          let totalDataKel = 0;
          data.dataSensor.forEach(element => {
            if (element.flag_sensor.includes('suhu')) {
              element.value.forEach(element2 => {
                suhuTotal += parseFloat(element2);
                totalDataSuhu += 1;
              });
            }
          });

          data.dataSensor.forEach(element => {
            if (element.flag_sensor.includes('kelembaban')) {
              element.value.forEach(element2 => {
                kelTotal += parseFloat(element2);
                totalDataKel += 1;
              });
            }
          });

          let rataRataSuhu = (suhuTotal / totalDataSuhu).toFixed(2);
          let rataRataKel = (kelTotal / totalDataKel).toFixed(2);

          $('#suhu-rata-rata')[0].innerHTML = rataRataSuhu;
          $('#kelembaban-rata-rata')[0].innerHTML = rataRataKel;

          if (rataRataSuhu > 20 && rataRataSuhu < 30) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Normal';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-success');
          } else if (rataRataSuhu > 30 && rataRataSuhu < 50) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          } else if (rataRataSuhu > 50 && rataRataSuhu < 100) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Bahaya';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-danger');
          } else {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          }

          if (rataRataKel > 80) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Normal';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-success');
          } else if (rataRataKel > 60) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Peringatan';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-warning');
          } else if (rataRataKel > 0) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Bahaya';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          } else {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Kesalahan!';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          }

          let dataSuhuTemp = [
            [],
            []
          ];
          let dataResultSuhuTemp = [];
          data.dataSensor.forEach(element => {
            if (element.flag_sensor.includes('suhu_1')) {
              dataSuhuTemp[0].push(element.value);
            }

            if (element.flag_sensor.includes('suhu_2')) {
              dataSuhuTemp[1].push(element.value);
            }
          });

          for (var i = 0; i < dataSuhuTemp[0][0].length; i++) {
            dataResultSuhuTemp.push((parseInt(dataSuhuTemp[0][0][i]) + parseInt(dataSuhuTemp[1][0][0])) / 2);
          }

          let dataKelTemp = [
            [],
            []
          ];
          let dataResultKelTemp = [];
          data.dataSensor.forEach(element => {
            if (element.flag_sensor.includes('kelembaban_1')) {
              dataKelTemp[0].push(element.value);
            }

            if (element.flag_sensor.includes('kelembaban_2')) {
              dataKelTemp[1].push(element.value);
            }
          });

          for (var i = 0; i < dataKelTemp[0][0].length; i++) {
            dataResultKelTemp.push((parseInt(dataKelTemp[0][0][i]) + parseInt(dataKelTemp[1][0][i])) / 2);
          }

          apexSuhu.updateOptions({
            series: [{
              name: 'Suhu (°C)',
              data: dataResultSuhuTemp
            }],
            xaxis: {
              categories: data.dataWaktuSensor[1].value
            }
          });
          apexKelembaban.updateOptions({
            series: [{
              name: 'Kelembaban (%)',
              data: dataResultKelTemp
            }],
            xaxis: {
              categories: data.dataWaktuSensor[0].value
            }
          });
          apexSuhuDanKelembaban.updateOptions({
            series: [{
              name: 'Kelembaban (%)',
              data: dataResultKelTemp
            }, {
              name: 'Suhu (°C)',
              data: dataResultSuhuTemp
            }],
            xaxis: {
              categories: data.dataWaktuSensor[1].value
            }
          });

          $('#total-suhu').text(dataResultSuhuTemp.length);
          $('#total-kelembaban').text(dataResultKelTemp.length);
          $('#total-suhu-dan-kelembaban').text(dataResultSuhuTemp.length);

          // apexSuhu.render();
          // apexKelembaban.render();
          // apexSuhuDanKelembaban.render();
        }
      });
    }
    initializeCharts();
    setInterval(getDataSensor, 1000);

    // getDataSensor();
  </script>
@endsection