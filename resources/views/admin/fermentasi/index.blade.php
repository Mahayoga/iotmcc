@extends('admin.layouts.main')

@section('title', 'Dashboard')

@section('content')

  <main class="admin-main">
    <div class="container-fluid p-4 p-lg-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-0">Ruangan Fermentasi</h1>
          <p class="text-muted mb-0">Rekap Ruang Fermentasi Vanili Agrofilia Permata</p>
        </div>
      </div>

      <!-- Rekap Suhu & Kelembaban -->
      <div class="row g-4 mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-transparent border-0">
              <h5 class="card-title mb-1 mt-2">Rekap Ruang Fermentasi</h5>
              <small class="text-muted">Pantauan Kondisi di Ruang Fermentasi</small>
            </div>

            <div class="card-body">
              <div class="row gy-4">

                <!-- Card Suhu -->
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

                <!-- Card Kelembaban -->
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

      <!--Grafik-->
      <div class="row mt-4">
        <!-- Grafik Suhu -->
        <div class="col-lg-12 mb-4">
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
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span id="total-suhu">-</span> data terakhir <span id="status-suhu"></span></small>
              </div>
            </div>
          </div>
        </div>

        <!-- Grafik Kelembapan -->
        <div class="col-lg-12 mb-4">
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
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span id="total-kelembaban">-</span> data terakhir <span id="status-kelembaban"></span></small>
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
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span id="total-suhu-dan-kelembaban">-</span> data terakhir <span id="status-suhu-dan-kelembaban"></span></small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Std Dev Suhu -->
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Kestabilan Suhu dalam Ruangan</h5>
                <small class="text-muted">Seberapa stabil suhu di ruang fermentasi</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartStddevSuhu"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span id="total-stddev-suhu">-</span> data terakhir <span id="status-stddev-suhu"></span></small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Std Dev Kelembaban -->
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 mt-2">Kestabilan Kelembaban dalam Ruangan</h5>
                <small class="text-muted">Seberapa stabil kelembaban di ruang fermentasi</small>
              </div>
            </div>
            <div class="card-body">
              <div id="chartStddevKelembaban"></div>
              <div class="p-4">
                <small class="text-muted">*data yang ditampilkan adalah rata rata selama 15 menit dengan total <span id="total-stddev-kelembaban">-</span> data terakhir <span id="status-stddev-kelembaban"></span></small>
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
    let apexStddevSuhu = null;
    let apexStddevKelembaban = null;

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
        noData: {
          text: 'Tidak ada data yang masuk untuk ditampilkan hari ini (periksa riwayat data untuk lebih lanjut)',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: '#888',
            fontSize: '16px',
            fontFamily: 'Helvetica'
          }
        }
      }
      let options2 = {
        chart: {
          type: 'line',
          height: '350px',
        },
        series: [{
          name: '?',
          data: []
        }],
        xaxis: {
          type: 'datetime',
          categories: []
        },
        stroke: {
          curve: 'smooth'
        },
        markers: {
          size: 5
        },
        noData: {
          text: 'Tidak ada data yang masuk untuk ditampilkan hari ini (periksa riwayat data untuk lebih lanjut)',
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: '#888',
            fontSize: '16px',
            fontFamily: 'Helvetica'
          }
        }
      }

      apexSuhu = new ApexCharts($('#chartSuhu')[0], options);
      apexKelembaban = new ApexCharts($('#chartKelembaban')[0], options);
      apexSuhuDanKelembaban = new ApexCharts($('#chartSuhuDanKelembaban')[0], options);
      apexStddevSuhu = new ApexCharts($('#chartStddevSuhu')[0], options2);
      apexStddevKelembaban = new ApexCharts($('#chartStddevKelembaban')[0], options2);

      apexSuhu.render();
      apexKelembaban.render();
      apexSuhuDanKelembaban.render();
      apexStddevSuhu.render();
      apexStddevKelembaban.render();

      apexSuhu.updateSeries([]);
      apexKelembaban.updateSeries([]);
      apexSuhuDanKelembaban.updateSeries([]);
      apexStddevSuhu.updateSeries([]);
      apexStddevKelembaban.updateSeries([]);
    }

    function getDataSensor() {
      $.get('{{ route('ruang-fermentasi.getDataSensor', ['11dc76a4-3c99-4563-9bbe-e1916a4a4ff2']) }}', {

      }, function (data, status) {
        if (data.status == true) {
          let classListSuhu = document.getElementById('status-suhu-ruangan').classList;
          let classListKelembaban = document.getElementById('status-kelembaban-ruangan').classList;
          apexSuhu.updateSeries([]);
          apexKelembaban.updateSeries([]);
          apexSuhuDanKelembaban.updateSeries([]);
          apexStddevSuhu.updateSeries([]);
          apexStddevKelembaban.updateSeries([]);

          apexSuhu.updateOptions({
            xaxis: {
              categories: data.dataWaktuSensor[0].value
            }
          });
          apexKelembaban.updateOptions({
            xaxis: {
              categories: data.dataWaktuSensor[0].value
            }
          });
          apexSuhuDanKelembaban.updateOptions({
            xaxis: {
              categories: data.dataWaktuSensor[0].value
            }
          });
          apexStddevSuhu.updateOptions({
            yaxis: { title: { text: 'Std Dev Suhu' } },
            annotations: {
              yaxis: [
                { y: 1.0, borderColor: '#d40624', label: { text: 'batas kestabilan suhu' }},
              ],
            }
          });
          apexStddevKelembaban.updateOptions({
            yaxis: { title: { text: 'Std Dev Kelembaban' } },
            annotations: {
              yaxis: [
                { y: 5.0, borderColor: '#d40624', label: { text: 'batas kestabilan kelembaban' }},
              ],
            }
          });
          // $('#total-suhu').text(dataResultSuhuTemp.length);
          // $('#total-kelembaban').text(dataResultKelTemp.length);
          // $('#total-suhu-dan-kelembaban').text(dataResultSuhuTemp.length);
          // { y2: 5.0, borderColor: '#d40624', label: { text: 'batas kestabilan kelembaban' }}

          data.dataSensor.forEach(element => {
            $('#total-suhu').text(element.value.length);
            $('#total-kelembaban').text(element.value.length);
            $('#total-suhu-dan-kelembaban').text(element.value.length);
            $('#total-stddev-suhu').text(element.value.length);
            $('#total-stddev-kelembaban').text(element.value.length);
            if(element.flag_sensor == 'suhu_1') {
              apexSuhu.appendSeries({
                name: 'Suhu 1 (°C)',
                data: element.value
              });
              apexSuhuDanKelembaban.appendSeries({
                name: 'Suhu 1 (°C)',
                data: element.value
              });
              apexStddevSuhu.appendSeries({
                name: "Suhu 1 (stddev)",
                data: element.stddev
              });
            } else if(element.flag_sensor == 'kelembaban_1') {
              apexKelembaban.appendSeries({
                name: 'Kelembaban 1 (%)',
                data: element.value
              });
              apexSuhuDanKelembaban.appendSeries({
                name: 'Kelembaban 1 (%)',
                data: element.value
              });
              apexStddevKelembaban.appendSeries({
                name: "Kelembaban 1 (stddev)",
                data: element.stddev
              });
            } else if(element.flag_sensor == 'suhu_2') {
              apexSuhu.appendSeries({
                name: 'Suhu 2 (°C)',
                data: element.value
              });
              apexSuhuDanKelembaban.appendSeries({
                name: 'Suhu 2 (°C)',
                data: element.value
              });
              apexStddevSuhu.appendSeries({
                name: "Suhu 2 (stddev)",
                data: element.stddev
              });
            } else if(element.flag_sensor == 'kelembaban_2') {
              apexKelembaban.appendSeries({
                name: 'Kelembaban 2 (%)',
                data: element.value
              });
              apexSuhuDanKelembaban.appendSeries({
                name: 'Kelembaban 2 (%)',
                data: element.value
              });
              apexStddevKelembaban.appendSeries({
                name: "Kelembaban 2 (stddev)",
                data: element.stddev
              });
            }
          });

          $('#suhu-rata-rata')[0].innerHTML = data.currentSuhu;
          $('#kelembaban-rata-rata')[0].innerHTML = data.currentKelembaban;

          if (parseFloat(data.currentSuhu) > 20 && parseFloat(data.currentSuhu) < 30) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Normal';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-success');
          } else if (parseFloat(data.currentSuhu) > 30 && parseFloat(data.currentSuhu) < 50) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          } else if (parseFloat(data.currentSuhu) > 50 && parseFloat(data.currentSuhu) < 100) {
            $('#status-suhu-ruangan')[0].innerHTML = 'Bahaya';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-danger');
          } else {
            $('#status-suhu-ruangan')[0].innerHTML = 'Peringatan';
            classListSuhu.remove('text-success', 'text-warning', 'text-danger');
            classListSuhu.add('text-warning');
          }

          if (parseFloat(data.currentKelembaban) > 80) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Normal';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-success');
          } else if (parseFloat(data.currentKelembaban) > 60) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Peringatan';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-warning');
          } else if (parseFloat(data.currentKelembaban) > 0) {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Bahaya';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          } else {
            $('#status-kelembaban-ruangan')[0].innerHTML = 'Kesalahan!';
            classListKelembaban.remove('text-success', 'text-warning', 'text-danger');
            classListKelembaban.add('text-danger');
          }
        }
      });
    }
    initializeCharts();
    setInterval(getDataSensor, 60000);

    getDataSensor();
  </script>
@endsection