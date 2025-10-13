<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>IOTMCC</title>

  <!-- CSS FILES -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap"
    rel="stylesheet">
  <link href="{{ asset('assets/user/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/user/css/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/user/css/templatemo-topic-listing.css') }}" rel="stylesheet">
</head>

<body id="top">
  <main>
    <nav class="navbar navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand">
          <img src="{{ asset('assets/user/images/icon-iotmcc.png') }}" alt="IoTMCC Logo" width="65" height="65"
            class="me-2">
        </a>
        <div class="d-lg-none ms-auto me-4">
          <a href="#top" class="navbar-icon bi-person smoothscroll"></a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-lg-5 me-lg-auto">
            <li class="nav-item">
              <a class="nav-link click-scroll" href="#section_1">Home</a>
            </li>

            <li class="nav-item">
              <a class="nav-link click-scroll" href="#section_2">Galeri</a>
            </li>

            <li class="nav-item">
              <a class="nav-link click-scroll" href="#section_3">Mekanisme</a>
            </li>

            {{-- <li class="nav-item">
              <a class="nav-link click-scroll" href="#section_4">FAQs</a>
            </li> --}}

            <li class="nav-item">
              <a class="nav-link click-scroll" href="#section_5">Lokasi</a>
            </li>
          </ul>
          <div class="d-none d-lg-block">
            @if(session('is_logged_in'))
              <a href="{{ route('dashboard.index') }}" class="btn btn-primary smoothscroll">Dashboard</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-primary smoothscroll">Login</a>
            @endif
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero section -->
    <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-12 mx-auto">
            <h1 class="text-white text-center">IOTMCC</h1>
            <h6 class="text-center">Internet of Things Multi Climate Control</h6>
          </div>
        </div>
      </div>
    </section>

    <!-- Mekanisme Section (Timeline Horizontal Card Layout dengan Overlay) -->
<section class="timeline-section section-padding" id="section_2">
  <div class="overlay-block"></div> <!-- overlay custom -->

  <div class="container">
    <div class="col-12 text-center mb-5">
      <h2 class="mb-4 text-black">Bagaimana IoT MCC Berjalan?</h2>
      <p class="text-black-50">Langkah-langkah implementasi sistem IoT MCC dalam budidaya vanili.</p>
    </div>

    <div class="timeline-container">
      <ul class="timeline-cards horizontal-scroll">
        <!-- Step 1 -->
        <li class="card-item">
          <div class="card h-100 shadow-lg bg-dark text-white text-center">
            <div class="card-body">
              <div class="mb-3">
                <i class="bi bi-cpu display-4 text-primary"></i>
              </div>
              <h5 class="card-title mb-3">Perancangan Sistem IoT</h5>
              <p class="card-text">
                Tim IoT MCC memulai dengan merancang sistem yang mampu memantau dan mengontrol berbagai aspek budidaya vanili secara otomatis.
              </p>
            </div>
          </div>
        </li>

        <!-- Step 2 -->
        <li class="card-item">
          <div class="card h-100 shadow-lg bg-dark text-white text-center">
            <div class="card-body">
              <div class="mb-3">
                <i class="bi bi-gear display-4 text-success"></i>
              </div>
              <h5 class="card-title mb-3">Pengujian dan Implementasi</h5>
              <p class="card-text">
                Setelah perancangan, sistem diuji di lapangan untuk memastikan sensor dan aktuator bekerja sesuai kebutuhan budidaya vanili.
              </p>
            </div>
          </div>
        </li>

        <!-- Step 3 -->
        <li class="card-item">
          <div class="card h-100 shadow-lg bg-dark text-white text-center">
            <div class="card-body">
              <div class="mb-3">
                <i class="bi bi-bar-chart display-4 text-warning"></i>
              </div>
              <h5 class="card-title mb-3">Pemantauan dan Analisis Data</h5>
              <p class="card-text">
                Data dari sensor dikumpulkan dan dianalisis untuk meningkatkan efisiensi sistem serta membantu petani dalam pengambilan keputusan.
              </p>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>



    <!-- Galeri Section -->
    <section class="explore-section section-padding" id="section_3">
      <div class="container">
        <div class="col-12 text-center">
          <h2 class="mb-4">Galeri</h2>
          <p class="text-muted">
            Kumpulan dokumentasi kegiatan, proyek, dan inovasi dari tim IoT MCC Vanili.
          </p>
        </div>
        <div class="row">
          <!-- Item 1 -->
          <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="custom-block bg-white shadow-lg">
              <a href="#">
                <img src="{{ asset('assets/user/images/dummy1.jpg') }}" class="custom-block-image img-fluid"
                  alt="Rancang Alat IoT Pemantau Kelembapan">
                <div class="p-3">
                  <h5 class="mb-2">Rancang Alat IoT Pemantau Kelembapan</h5>
                  <p class="mb-0">
                    Pengembangan sistem pemantauan kelembapan tanah untuk budidaya vanili.
                  </p>
                </div>
              </a>
            </div>
          </div>
          <!-- Item 2 -->
          <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="custom-block bg-white shadow-lg">
              <a href="#">
                <img src="{{ asset('assets/user/images/dummy1.jpg') }}" class="custom-block-image img-fluid"
                  alt="Uji Lapangan Sistem IoT">
                <div class="p-3">
                  <h5 class="mb-2">Uji Lapangan Sistem IoT</h5>
                  <p class="mb-0">
                    Tim melakukan pengujian sensor dan sistem otomatisasi di kebun vanili.
                  </p>
                </div>
              </a>
            </div>
          </div>
          <!-- Item 3 -->
          <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="custom-block bg-white shadow-lg">
              <a href="#">
                <img src="{{ asset('assets/user/images/dummy1.jpg') }}" class="custom-block-image img-fluid"
                  alt="Presentasi Proyek IoT MCC Vanili">
                <div class="p-3">
                  <h5 class="mb-2">Presentasi Proyek IoT MCC Vanili</h5>
                  <p class="mb-0">
                    Anggota tim mempresentasikan hasil riset di hadapan dosen dan mitra industri.
                  </p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>


    <footer class="site-footer section-bg text-white py-4">
      <div class="container">
        <div class="row">

          <!-- Peta Lokasi -->
          <div class="col-lg-3 col-md-6 mb-3">
            <h6 class="site-footer-title mb-2">Lokasi Kami</h6>
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.73643959526!2d113.71969197484798!3d-8.128293291901382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695007abe851f%3A0x984e5c4f84e4956b!2sVanili%20Agrofilia%20Permata!5e0!3m2!1sid!2sid!4v1759897839593!5m2!1sid!2sid"
              width="100%" height="150" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            <p class="small mt-1">Area Sawah/Kulon, Baratan, Kec. Patrang, Kabupaten Jember, Jawa Timur</p>
          </div>

          <!-- Kebun -->
          <div class="col-lg-3 col-md-6 mb-3">
            <h6 class="footer-title mb-2">Kebun</h6>
            <p class="small">Area Sawah/Kulon, Baratan, Kec. Patrang, Kabupaten Jember, Jawa Timur</p>
            <p class="mb-1 small"><i class="bi bi-telephone me-2"></i> <a href="tel:08159200022"
                class="footer-link">0815 9200022</a></p>
            <p class="small"><i class="bi bi-envelope me-2"></i> <a href="mailto:info@permataindonesia.com"
                class="footer-link">info@permataindonesia.com</a></p>
          </div>

          <!-- Head Office -->
          <div class="col-lg-3 col-md-6 mb-3">
            <h6 class="footer-title mb-2">Head Office</h6>
            <p class="small">Jl. Kyai Maja No.7a, RT.4/RW.1, Grogol Sel., Kebayoran Baru, Jakarta Selatan 12130</p>
            <p class="mb-1 small"><i class="bi bi-telephone me-2"></i> <a href="tel:08159200022"
                class="footer-link">0815 9200022</a></p>
            <p class="small"><i class="bi bi-envelope me-2"></i> <a href="mailto:info@permataindonesia.com"
                class="footer-link">info@permataindonesia.com</a></p>
          </div>

          <!-- Navigasi + Sosmed -->
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h6 class="footer-title mb-2">Navigasi</h6>
                <ul class="footer-links mb-0 small">
                  <li><a href="#" class="footer-link">Home</a></li>
                  <li><a href="#" class="footer-link">Galeri</a></li>
                  <li><a href="#" class="footer-link">Mekanisme</a></li>
                  <li><a href="#" class="footer-link">Lokasi</a></li>
                </ul>
              </div>
              <div class="ms-2">
                <h6 class="footer-title mb-2">Medsos</h6>
                <div class="social-icons d-flex flex-column gap-1">
                  <a href="#" class="social-icon"><i class="bi bi-facebook"></i> facebook</a>
                  <a href="#" class="social-icon"><i class="bi bi-instagram"></i> Instagram</a>
                  <a href="#" class="social-icon"><i class="bi bi-youtube"></i> Youtube</a>
                </div>
              </div>
            </div>
          </div>

        </div>

        <hr class="footer-divider my-3">

        <div class="row">
          <div class="col-12 text-center">
            <p class="mb-0 small">© 2025 IOTMCC. All rights reserved.</p>
          </div>
        </div>
      </div>
    </footer>



    <!-- JAVASCRIPT FILES -->
    <script src="{{ asset('assets/user/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/user/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/user/js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('assets/user/js/click-scroll.js') }}"></script>
    <script src="{{ asset('assets/user/js/custom.js') }}"></script>

</body>

</html>