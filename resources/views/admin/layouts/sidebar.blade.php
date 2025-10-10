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
        <li class="nav-item mt-3">
          <small class="text-muted px-3 text-uppercase fw-bold">--- Separator ---</small>
        </li>
      </ul>
    </nav>
  </div>
</aside>