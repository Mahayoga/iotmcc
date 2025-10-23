<!-- Header -->
<header class="admin-header">
  <nav class="navbar navbar-expand-lg" style="background-color: #e8f5e9; border-bottom: 1px solid #c8e6c9;">
    <div class="container-fluid">
      <!-- Logo/Brand - Now first on the left -->
      <a class="navbar-brand d-flex align-items-center" href="{{ route('user.index') }}">
        <img
          src="{{ asset('assets/admin/images/icon-iotmcc.png') }}"
          alt="Logo" height="30" class="d-inline-block align-text-top me-2">
        <h1 class="h4 mb-0 fw-bold" style="color: #000000">Vanili Internet of Things</h1>
      </a>

      <!-- Right Side Icons -->
      <div class="navbar-nav flex-row">

        <!-- User Menu -->
        <div class="dropdown">
          <button class="btn btn-outline-secondary d-flex align-items-center" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <img
              src="data:image/svg+xml,%3csvg%20width='32'%20height='32'%20viewBox='0%200%2032%2032'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3c!--%20Background%20circle%20--%3e%3ccircle%20cx='16'%20cy='16'%20r='16'%20fill='url(%23avatarGradient)'/%3e%3c!--%20Person%20silhouette%20--%3e%3cg%20fill='white'%20opacity='0.9'%3e%3c!--%20Head%20--%3e%3ccircle%20cx='16'%20cy='12'%20r='5'/%3e%3c!--%20Body%20--%3e%3cpath%20d='M16%2018c-5.5%200-10%202.5-10%207v1h20v-1c0-4.5-4.5-7-10-7z'/%3e%3c/g%3e%3c!--%20Subtle%20border%20--%3e%3ccircle%20cx='16'%20cy='16'%20r='15.5'%20fill='none'%20stroke='rgba(255,255,255,0.2)'%20stroke-width='1'/%3e%3c!--%20Gradient%20definition%20--%3e%3cdefs%3e%3clinearGradient%20id='avatarGradient'%20x1='0%25'%20y1='0%25'%20x2='100%25'%20y2='100%25'%3e%3cstop%20offset='0%25'%20style='stop-color:%236b7280;stop-opacity:1'%20/%3e%3cstop%20offset='100%25'%20style='stop-color:%234b5563;stop-opacity:1'%20/%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e"
              alt="User Avatar" width="24" height="24" class="rounded-circle me-2">
            <span class="d-none d-md-inline">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
            <i class="bi bi-chevron-down ms-1"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <div class="dropdown-item">
                <form action="{{ route('logout') }}" method="post">
                  @csrf
                  <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                  </button>
                </form>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>