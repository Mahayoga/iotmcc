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

        {{-- Rekap Kondisi Ruang Di Gudang --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 18px;">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-1 mt-2">Rekap Ruang Gudang</h5>
                        <small class="text-muted">Pantauan Kondisi di Setiap Ruang Gudang Vanili</small>
                    </div>

                    <div class="card-body">
                        <div class="row gy-4">
                            {{-- Card Bleaching --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="gudang-box gudang-1">
                                    <div class="gudang-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="gudang-icon bg-white text-success">
                                                <i class="bi bi-fire fs-4"></i>
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-0 fw-bold">{{ $dataRuangan[1]['nama_ruangan'] }}</h6>
                                            </div>
                                        </div>
                                        <span class="badge px-3 py-2 bg-white border text-{{ $dataRuangan[1]['status_color'] }}">
                                            {{ $dataRuangan[1]['status_icon'] }} {{ $dataRuangan[1]['status'] }}
                                        </span>
                                    </div>
                                    <div class="gudang-main mt-3">
                                        @if($dataRuangan[1]['suhu_bleaching'])
                                            <h2 class="fw-bold mb-0">{{ $dataRuangan[1]['suhu_bleaching'] }}°C</h2>
                                            <p class="text-dark small mb-2">
                                                <strong>Suhu Terakhir (07:00 - 10:00)</strong>
                                            </p>
                                        @else
                                            <h2 class="fw-bold mb-0 text-muted">-</h2>
                                            <p class="text-dark small mb-2">
                                                <strong>Suhu Terakhir (07:00 - 10:00)</strong>
                                            </p>
                                            <p class="text-muted small mb-0">
                                                <i>Belum ada data hari ini</i>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Card Fermentasi  --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="gudang-box gudang-2">
                                    <div class="gudang-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="gudang-icon bg-white text-info">
                                                <i class="bi bi-sun fs-4"></i>
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-0 fw-bold">{{ $dataRuangan[2]['nama_ruangan'] }}</h6>
                                            </div>
                                        </div>
                                        <span class="badge px-3 py-2 bg-white border text-{{ $dataRuangan[2]['status_color'] }}">
                                            {{ $dataRuangan[2]['status_icon'] }} {{ $dataRuangan[2]['status'] }}
                                        </span>
                                    </div>
                                    <div class="gudang-main mt-3">
                                        <h2 class="fw-bold mb-0">{{ $dataRuangan[2]['suhu'] }}°C</h2>
                                        <p class="text-dark small mb-2">
                                            Kelembapan: <strong>{{ $dataRuangan[2]['kelembapan'] }}%</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Pengeringan  --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="gudang-box gudang-3">
                                    <div class="gudang-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="gudang-icon bg-white text-warning">
                                                <i class="bi bi-fan fs-4"></i>
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-0 fw-bold">{{ $dataRuangan[3]['nama_ruangan'] }}</h6>
                                            </div>
                                        </div>
                                        <span class="badge px-3 py-2 bg-white border text-{{ $dataRuangan[3]['status_color'] }}">
                                            {{ $dataRuangan[3]['status_icon'] }} {{ $dataRuangan[3]['status'] }}
                                        </span>
                                    </div>
                                    <div class="gudang-main mt-3">
                                        <h2 class="fw-bold mb-0">{{ $dataRuangan[3]['suhu'] }}°C</h2>
                                        <p class="text-dark small mb-2">
                                            Kelembapan: <strong>{{ $dataRuangan[3]['kelembapan'] }}%</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik  --}}
        <div class="row mt-4">
            <!-- Grafik Bleaching -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-1 mt-2">Grafik Suhu Alat Bleaching</h5>
                        <small class="text-muted">Perubahan Suhu Alat (Jam 7-10 Pagi dengan Interval 10 Menit)</small>
                    </div>
                    <div class="card-body" style="height: 300px;">
                        <canvas id="chartBleaching" style="width:100%; height:100%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik Suhu-->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-1 mt-2">Grafik Suhu Ruangan</h5>
                        <small class="text-muted">Fermentasi & Pengeringan</small>
                    </div>
                    <div class="card-body" style="height: 300px;">
                        <canvas id="chartSuhu" style="width:100%; height:100%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik Kelembapan -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-1 mt-2">Grafik Kelembapan</h5>
                        <small class="text-muted">Fermentasi & Pengeringan</small>
                    </div>
                    <div class="card-body" style="height: 300px;">
                        <canvas id="chartKelembapan" style="width:100%; height:100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const grafikSuhu = @json($grafikSuhu ?? []);
    const grafikKelembapan = @json($grafikKelembapan ?? []);
    const grafikBleaching = @json($grafikBleaching ?? []);

    // Grafik Bleaching
    const ctxBleaching = document.getElementById('chartBleaching')?.getContext('2d');
    if (ctxBleaching && Object.keys(grafikBleaching).length > 0) {
        const bleachingDatasets = [];
        Object.keys(grafikBleaching).forEach((namaRuangan, index) => {
            const dataRuangan = grafikBleaching[namaRuangan];
            bleachingDatasets.push({
                label: 'Suhu Alat ' + namaRuangan,
                data: dataRuangan.map(item => item.nilai),
                borderColor: '#FF6B6B',
                backgroundColor: 'rgba(255,107,107,0.1)',
                tension: 0.4,
                borderWidth: 3
            });
        });

        new Chart(ctxBleaching, {
            type: 'line',
            data: {
                labels: grafikBleaching[Object.keys(grafikBleaching)[0]]?.map(item => item.waktu) ?? [],
                datasets: bleachingDatasets
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Grafik Suhu
    const ctxSuhu = document.getElementById('chartSuhu')?.getContext('2d');
    if (ctxSuhu && Object.keys(grafikSuhu).length > 0) {
        const suhuDatasets = [];
        Object.keys(grafikSuhu).forEach((namaRuangan, index) => {
            const dataRuangan = grafikSuhu[namaRuangan];
            suhuDatasets.push({
                label: namaRuangan,
                data: dataRuangan.map(item => item.nilai),
                borderColor: ['#00A86B', '#FFD93D'][index] || '#888',
                backgroundColor: ['rgba(0,168,107,0.1)', 'rgba(255,217,61,0.1)'][index] || 'rgba(136,136,136,0.1)',
                tension: 0.4,
                borderWidth: 3
            });
        });

        new Chart(ctxSuhu, {
            type: 'line',
            data: {
                labels: grafikSuhu[Object.keys(grafikSuhu)[0]]?.map(item => item.waktu) ?? [],
                datasets: suhuDatasets
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Grafik Kelembapan
    const ctxKelembapan = document.getElementById('chartKelembapan')?.getContext('2d');
    if (ctxKelembapan && Object.keys(grafikKelembapan).length > 0) {
        const kelembapanDatasets = [];
        Object.keys(grafikKelembapan).forEach((namaRuangan, index) => {
            const dataRuangan = grafikKelembapan[namaRuangan];
            kelembapanDatasets.push({
                label: namaRuangan,
                data: dataRuangan.map(item => item.nilai),
                borderColor: ['#00A86B', '#FFD93D'][index] || '#888',
                backgroundColor: ['rgba(0,168,107,0.1)', 'rgba(255,217,61,0.1)'][index] || 'rgba(136,136,136,0.1)',
                tension: 0.4,
                borderWidth: 3
            });
        });

        new Chart(ctxKelembapan, {
            type: 'line',
            data: {
                labels: grafikKelembapan[Object.keys(grafikKelembapan)[0]]?.map(item => item.waktu) ?? [],
                datasets: kelembapanDatasets
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});
</script>
@endsection