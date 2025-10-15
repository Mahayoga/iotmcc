<!-- Sidebar -->
<aside class="admin-sidebar" id="admin-sidebar">
  <div class="sidebar-content">
    <nav class="sidebar-nav">
      <ul class="nav flex-column">
        @if(Route::currentRouteName() == 'dashboard.index')
          <li class="nav-item p-2">
            <a class="nav-link active" href="{{ route('dashboard.index') }}">
              <i class="bi bi-speedometer2"></i>
              <span>Dashboard</span>
            </a>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
              <i class="bi bi-speedometer2"></i>
              <span>Dashboard</span>
            </a>
          </li>
        @endif

        <!-- Menu Gudang -->
        <li class="nav-item mt-3 mb-1">
          <small class="text-muted px-3 text-uppercase fw-bold">Kelola Gudang</small>
        </li>
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
                  <i class="bi bi-columns"></i>
                  <span>Ruang Perebusan</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./elements.html">
                  <i class="bi bi-columns"></i>
                  <span>Ruang 2</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./elements.html">
                  <i class="bi bi-columns"></i>
                  <span>Ruang 3</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Menu Kelola Data -->
        <li class="nav-item mt-3 mb-1">
          <small class="text-muted px-3 text-uppercase fw-bold">Kelola Riwayat Data</small>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRiwayatData" aria-expanded="false">
            <i class="bi bi-houses"></i>
            <span>Riwayat Data</span>
            <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <div class="collapse" id="collapseRiwayatData">
            <ul class="nav nav-submenu">
              <li class="nav-item p-2">
                <a class="nav-link" href="./elements.html">
                  <i class="bi bi-activity"></i>
                  <span>Histori Data</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </nav>
  </div>
</aside>