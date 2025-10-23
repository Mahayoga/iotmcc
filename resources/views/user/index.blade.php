<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>VIoT</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ ('assets/user/img/favicon.png') }}" rel="icon">
  <link href="{{ ('assets/user/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ ('assets/user/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ ('assets/user/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ ('assets/user/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ ('assets/user/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ ('assets/user/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ ('assets/user/css/main.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Company
  * Template URL: https://bootstrapmade.com/company-free-html-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <img src="{{ asset('assets/user/images/icon-iotmcc.png') }}" alt="IoTMCC Logo" width="35" height="35"
          class="me-2">
        <h3 class="sitename m-0">VIoT</h3><span>.</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="">Home</a></li>
          <li><a href="#about" class="">About</a></li>
          <li><a href="#services" class="">Services</a></li>
          <li><a href="#gallery" class="">Gallery</a></li>
          <li>@if(session('is_logged_in'))
            <a href="{{ route('dashboard.index') }}" class="">Dashboard</a>
          @else
              <a href="{{ route('login') }}" class="">Login</a>
            @endif
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <div class="hero-single">
        <img src="{{ ('assets/user/images/background-vanili.jpg') }}" alt="Hero Image">
        <div class="container">
          <h2>VIoT</h2>
          <p>Vanilini Internet of Things</p>
        </div>
      </div>
    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container">
        <div class="row position-relative">
          <div class="col-lg-7 about-img" data-aos="zoom-out" data-aos-delay="200"><img
              src="{{ ('assets/user/images/isometric-warehouse.jpg') }}"></div>
          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
            <h2 class="inner-title">About</h2>
            <div class="our-story">
              <h4>Est 2023</h4>
              <h3>Our Story: Kualitas Vanili Premium</h3>
              <p>Kami berdedikasi untuk menghasilkan vanili dengan aroma dan rasa terbaik, berawal dari biji vanili
                hijau yang dipanen secara teliti.
                Gudang kami adalah pusat transformasi, tempat keajaiban rasa vanili diciptakan melalui proses pengolahan
                tradisional dan terstandar.
                Kualitas vanili kami terjamin melalui setiap tahapan kritis yang kami jalankan dengan penuh perhatian.
              </p>
              <ul>
                <li><i class="bi bi-check-circle"></i> <span>Perebusan (Blanching): Mematikan proses vegetatif dan
                    memulai pengembangan aroma.</span></li>
                <li><i class="bi bi-check-circle"></i> <span>Pendinginan Cepat: Menghentikan perebusan untuk menjaga
                    tekstur dan kualitas biji.</span></li>
                <li><i class="bi bi-check-circle"></i> <span>Fermentasi dan Pengeringan: Proses kunci pembentukan
                    senyawa vanilin dan pematangan aroma.</span></li>
              </ul>
              <p>Setiap biji vanili melalui siklus perebusan, pendinginan, dan fermentasi yang dikontrol ketat untuk
                mengaktifkan enzim,
                mengubah glukosida menjadi vanilin, dan mengembangkan profil rasa yang kaya dan mendalam.Komitmen kami
                pada proses ini
                memastikan bahwa setiap produk vanili yang keluar dari gudang kami adalah vanili kualitas premium.</p>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item item-cyan position-relative">
              <div class="icon">
                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                  <path stroke="none" stroke-width="0" fill="#f5f5f5"
                    d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174">
                  </path>
                </svg>
                <i class="bi bi-buildings"></i>
              </div>
              <h3>Jasa Pengolahan</h3>
              </a>
              <p>Memastikan vanili memiliki aroma, rasa, dan kadar air yang sesuai standar pasar. Layanan ini mencakup:
                Sortasi, perebusan, pendinginan, fermentasi, dan pengeringan.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item item-orange position-relative">
              <div class="icon">
                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                  <path stroke="none" stroke-width="0" fill="#f5f5f5"
                    d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426">
                  </path>
                </svg>
                <i class="bi bi-graph-up"></i>
              </div>
              <h3>Pengadaan Bahan Baku dan Kemitraan Petani</h3>
              </a>
              <p>menyediakan layanan terintegrasi, mulai dari Pembelian Biji Vanili Basah yang belum diolah langsung
                dari petani mitra,
                hingga Pendampingan Petani secara berkelanjutan melalui pelatihan dan dukungan teknis.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item item-teal position-relative">
              <div class="icon">
                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                  <path stroke="none" stroke-width="0" fill="#f5f5f5"
                    d="M300,541.5067337569781C382.14930387511276,545.0595476570109,479.8736841581634,548.3450877840088,526.4010558755058,480.5488172755941C571.5218469581645,414.80211281144784,517.5187510058486,332.0715597781072,496.52539010469104,255.14436215662573C477.37192572678356,184.95920475031193,473.57363656557914,105.61284051026155,413.0603344069578,65.22779650032875C343.27470386102294,18.654635553484475,251.2091493199835,5.337323636656869,175.0934190732945,40.62881213300186C97.87086631185822,76.43348514350839,51.98124368387456,156.15599469081315,36.44837278890362,239.84606092416172C21.716077023791087,319.22268207091537,43.775223500013084,401.1760424656574,96.891909868211,461.97329694683043C147.22146801428983,519.5804099606455,223.5754009179313,538.201503339737,300,541.5067337569781">
                  </path>
                </svg>
                <i class="bi bi-truck"></i>
              </div>

              <h3>Penjualan dan Distribusi Produk</h3>
              </a>
              <p>vanili yang telah diolah hingga mencapai standar kualitas diekspor langsung atau didistribusikan,
                meliputi beragam produk
                seperti Gourmet Vanilla Bean, Extraction Grade, Vanilla Powder, dan produk turunan lainnya.</p>
            </div>
          </div><!-- End Service Item -->
        </div>
      </div>
    </section><!-- /Services Section -->

    <!-- Portfolio Section -->
    <section id="gallery" class="gallery section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Gallery</h2>
        <p>Jelajahi galeri kami untuk melihat visual dari setiap tahap keunggulan kami</p>
      </div><!-- End Section Title -->
      <div class="container">
        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
        </div>
      </div>
    </section><!-- /Portfolio Section -->

  </main>

  <footer id="footer" class="footer dark-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="" class="logo d-flex align-items-center">
            <span class="sitename">VIoT</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Area Sawah/Kulon, Baratan,</p>
            <p> Kec. Patrang, Kabupaten Jember, Jawa Timur</p>
            <p class="mt-3"><strong>Phone:</strong> <span>08159200022</span></p>
            <p><strong>Email:</strong> <span>info@agrofilia.com</span></p>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#hero">Home</a></li>
            <li><a href="#about">About us</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#gallery">Gallery</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Maps Kami</h4>
          <p>Peta lokasi kebun dan gudang agrofilia vanili!</p>
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.73643959526!2d113.71969197484798!3d-8.128293291901382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695007abe851f%3A0x984e5c4f84e4956b!2sVanili%20Agrofilia%20Permata!5e0!3m2!1sid!2sid!4v1759897839593!5m2!1sid!2sid"
            width="100%" height="180" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Ours Social Media</h4>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">2025 IOTMCC.</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ ('assets/user/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ ('assets/user/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ ('assets/user/vendor/aos/aos.js') }}"></script>
  <script src="{{ ('assets/user/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ ('assets/user/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ ('assets/user/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ ('assets/user/vendor/waypoints/noframework.waypoints.js') }}"></script>
  <script src="{{ ('assets/user/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ ('assets/user/js/main.js') }}"></script>

</body>

</html>