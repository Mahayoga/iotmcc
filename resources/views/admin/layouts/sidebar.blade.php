<!-- Sidebar -->
<aside class="admin-sidebar" id="admin-sidebar">
  <div class="sidebar-content">
    <nav class="sidebar-nav">
      <ul class="nav flex-column">

        <!-- Dashboard -->
        @if(Route::currentRouteName() == 'dashboard.index')
          <li class="nav-item p-2">
            <a class="nav-link active" href="{{ route('dashboard.index') }}">
              <i class="bi bi-speedometer2"></i>
              <span>Dashboard</span>
            </a>
          </li>
        @else
          <li class="nav-item p-2">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
              <i class="bi bi-speedometer2"></i>
              <span>Dashboard</span>
            </a>
          </li>
        @endif

        <li class="nav-item mt-3 mb-1">
          <small class="text-muted px-3 text-uppercase fw-bold">Kelola Gudang</small>
        </li>

        @if(Route::currentRouteName() == 'ruang-perebusan.index' || Route::currentRouteName() == 'ruang-fermentasi.index' || Route::currentRouteName() == 'ruang-pengeringan.index')
          <li class="nav-item">
            <a class="nav-link active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseGudang" aria-expanded="true">
              <i class="bi bi-houses"></i>
              <span>Gudang</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse show" id="collapseGudang">
              <ul class="nav nav-submenu">

                <!-- Ruang Perebusan -->
                @if(Route::currentRouteName() == 'ruang-perebusan.index')
                  <li class="nav-item">
                    <a class="nav-link active" href="{{ route('ruang-perebusan.index') }}">
                      <i class="bi bi-fire"></i>
                      <span>Ruang Bleaching</span>
                    </a>
                  </li>
                @else
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('ruang-perebusan.index') }}">
                      <i class="bi bi-fire"></i>
                      <span>Ruang Bleaching</span>
                    </a>
                  </li>
                @endif

                <!-- Ruang Fermentasi -->
                @if(Route::currentRouteName() == 'ruang-fermentasi.index')
                  <li class="nav-item">
                    <a class="nav-link active" href="{{ route('ruang-fermentasi.index') }}">
                      <i class="bi bi-flask"></i>
                      <span>Ruang Fermentasi</span>
                    </a>
                  </li>
                @else
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('ruang-fermentasi.index') }}">
                      <i class="bi bi-flask"></i>
                      <span>Ruang Fermentasi</span>
                    </a>
                  </li>
                @endif

                <!-- Ruang Pengeringan -->
                @if(Route::currentRouteName() == 'ruang-pengeringan.index')
                  <li class="nav-item">
                    <a class="nav-link active" href="{{ route('ruang-pengeringan.index') }}">
                      <i class="bi bi-thermometer-sun"></i>
                      <span>Ruang Pengeringan</span>
                    </a>
                  </li>
                @else
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('ruang-pengeringan.index') }}">
                      <i class="bi bi-thermometer-sun"></i>
                      <span>Ruang Pengeringan</span>
                    </a>
                  </li>
                @endif
              </ul>
            </div>
          </li>
        @else

          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#collapseGudang" aria-expanded="false">
              <i class="bi bi-houses"></i>
              <span>Gudang</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="collapseGudang">
              <ul class="nav nav-submenu">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('ruang-perebusan.index') }}">
                    <i class="bi bi-fire"></i>
                    <span>Ruang Bleaching</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('ruang-fermentasi.index') }}">
                    <i class="bi bi-flask"></i> 
                    <span>Ruang Fermentasi</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('ruang-pengeringan.index') }}">
                    <i class="bi bi-thermometer-sun"></i>
                    <span>Ruang Pengeringan</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        <li class="nav-item mt-3 mb-1">
          <small class="text-muted px-3 text-uppercase fw-bold">Kelola Riwayat Data</small>
        </li>

        @if(Route::currentRouteName() == 'riwayat-data.index')
          <li class="nav-item">
            <a class="nav-link active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRiwayatData" aria-expanded="true">
              <i class="bi bi-clipboard2-data"></i>
              <span>Riwayat Data</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse show" id="collapseRiwayatData">
              <ul class="nav nav-submenu">

                <!-- Riwayat Data -->
                @if(Route::currentRouteName() == 'riwayat-data.index')
                  <li class="nav-item">
                    <a class="nav-link active" href="{{ route('riwayat-data.index') }}">
                      <i class="bi bi-clock-history"></i>
                      <span>Histori Data</span>
                    </a>
                  </li>
                @else
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('riwayat-data.index') }}">
                      <i class="bi bi-clock-history"></i>
                      <span>Histori Data</span>
                    </a>
                  </li>
                @endif
              </ul>
            </div>
          </li>
        @else

        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRiwayatData" aria-expanded="false">
              <i class="bi bi-clipboard2-data"></i>
              <span>Riwayat Data</span>
              <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="collapseRiwayatData">
              <ul class="nav nav-submenu">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('riwayat-data.index') }}">
                    <i class="bi bi-clock-history"></i>
                    <span>History Data</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif
      </ul>
    </nav>
  </div>
</aside>
