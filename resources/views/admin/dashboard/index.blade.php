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
                                                <h6 class="mb-0 fw-bold">{{ $dataRuangan[0]['nama_ruangan'] ?? 'Bleaching' }}</h6>
                                            </div>
                                        </div>
                                        {{-- <span class="badge px-3 py-2 {{ ($dataRuangan[0]['status'] ?? 'Perlu Cek') == 'Normal' ? 'bg-success text-white' : 'bg-warning text-dark' }}" data-ruangan="0">
                                            {{ $dataRuangan[0]['status'] ?? 'Perlu Cek' }}
                                        </span> --}}
                                    </div>
                                    <div class="gudang-main mt-3">
                                        @if($dataRuangan[0]['suhu_bleaching'])
                                            <h2 class="fw-bold mb-0">{{ $dataRuangan[0]['suhu_bleaching'] }}°C</h2>
                                            <p class="text-dark small mb-2">
                                                <strong>Suhu Terakhir Alat Bleaching (07:00 - 10:00)</strong>
                                            </p>
                                        @else
                                            <h2 class="fw-bold mb-0 text-muted">-</h2>
                                            <p class="text-dark small mb-2">
                                                <strong>Suhu Terakhir Alat Bleaching (07:00 - 10:00)</strong>
                                            </p>
                                            <p class="text-muted small mb-0">
                                                <i>Belum ada data hari ini</i>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Card Fermentasi --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="gudang-box gudang-2">
                                    <div class="gudang-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="gudang-icon bg-white text-info">
                                                <i class="bi bi-sun fs-4"></i>
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-0 fw-bold">{{ $dataRuangan[1]['nama_ruangan'] ?? 'Fermentasi' }}</h6>
                                            </div>
                                        </div>
                                        {{-- <span class="badge px-3 py-2 {{ ($dataRuangan[1]['status'] ?? 'Perlu Cek') == 'Normal' ? 'bg-success text-white' : 'bg-warning text-dark' }}" data-ruangan="1">
                                            {{ $dataRuangan[1]['status'] ?? 'Perlu Cek' }}
                                        </span> --}}
                                    </div>
                                    <div class="gudang-main mt-3">
                                        <h2 class="fw-bold mb-0">{{ $dataRuangan[1]['suhu'] ?? '-' }}°C</h2>
                                        <p class="text-dark small mb-2">
                                            Kelembapan: <strong>{{ $dataRuangan[1]['kelembapan'] ?? '-' }}%</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Pengeringan --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="gudang-box gudang-3">
                                    <div class="gudang-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="gudang-icon bg-white text-warning">
                                                <i class="bi bi-fan fs-4"></i>
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-0 fw-bold">{{ $dataRuangan[2]['nama_ruangan'] ?? 'Pengeringan' }}</h6>
                                            </div>
                                        </div>
                                        {{-- <span class="badge px-3 py-2 {{ ($dataRuangan[2]['status'] ?? 'Perlu Cek') == 'Normal' ? 'bg-success text-white' : 'bg-warning text-dark' }}" data-ruangan="2">
                                            {{ $dataRuangan[2]['status'] ?? 'Perlu Cek' }}
                                        </span> --}}
                                    </div>
                                    <div class="gudang-main mt-3">
                                        <h2 class="fw-bold mb-0">{{ $dataRuangan[2]['suhu'] ?? '-' }}°C</h2>
                                        <p class="text-dark small mb-2">
                                            Kelembapan: <strong>{{ $dataRuangan[2]['kelembapan'] ?? '-' }}%</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik Monitoring --}}
        <div class="row mt-4">
            <!-- Grafik Suhu Bleaching -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-1 mt-2">Grafik Suhu Bleaching</h5>
                        <small class="text-muted">Perubahan Suhu Alat (Jam 7-10 Pagi)</small>
                    </div>
                    <div class="card-body" style="height: 300px;">
                        <canvas id="chartBleaching" style="width:100%; height:100%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik Suhu Ruangan -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius:18px; background:#ffffff;">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-1 mt-2">Grafik Suhu Ruangan</h5>
                        <small class="text-muted">Perubahan Suhu Fermentasi & Pengeringan</small>
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
                        <small class="text-muted">Perubahan Kelembapan Fermentasi & Pengeringan</small>
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

    // Script status (belum selesai)
    const dataRuangan = @json($dataRuangan ?? []);
    updateRuanganStatus();

    function updateRuanganStatus() {
        dataRuangan.forEach((ruangan, index) => {
            const suhu = parseFloat(ruangan.suhu) || 0;
            const kelembapan = parseFloat(ruangan.kelembapan) || 0;
            const namaRuangan = ruangan.nama_ruangan.toLowerCase();

            let status = 'Perlu Cek';
            let badgeClass = 'bg-warning text-dark';

            if (namaRuangan.includes('bleaching') || index === 0) {
                if (suhu >= 50 && suhu <= 70) {
                    status = 'Normal';
                    badgeClass = 'bg-success text-white';
                }
            }
            else if (namaRuangan.includes('fermentasi') || index === 1) {
                if (suhu >= 28 && suhu <= 32 && kelembapan >= 75 && kelembapan <= 85) {
                    status = 'Normal';
                    badgeClass = 'bg-success text-white';
                }
            }
            else if (namaRuangan.includes('pengeringan') || index === 2) {
                if (suhu >= 25 && suhu <= 35 && kelembapan >= 40 && kelembapan <= 60) {
                    status = 'Normal';
                    badgeClass = 'bg-success text-white';
                }
            }

            const badgeElement = document.querySelector(`[data-ruangan="${index}"]`);
            if (badgeElement) {
                badgeElement.textContent = status;
                badgeClass = 'bg-success text-white';
            }
        });
    }
});
</script>
@endsection