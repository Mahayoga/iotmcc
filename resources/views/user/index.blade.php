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
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap"rel="stylesheet">
  <link href="{{ asset('assets/user/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/user/css/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/user/css/templatemo-topic-listing.css') }}" rel="stylesheet">
</head>

<body id="top">
  <main>
    <nav class="navbar navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand">
          <img src="{{ asset('assets/user/images/iotmcc-icon-trans.png') }}" alt="IoTMCC Logo" width="95" height="65 "
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
              <a class="nav-link click-scroll" href="#section_3">How it works</a>
            </li>

            {{-- <li class="nav-item">
              <a class="nav-link click-scroll" href="#section_4">FAQs</a>
            </li> --}}

            <li class="nav-item">
              <a class="nav-link click-scroll" href="#section_5">Lokasi</a>
            </li>
          </ul>
          <div class="d-none d-lg-block">
            <a href="#top" class="navbar-icon bi-person smoothscroll"></a>
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

    <section class="featured-section">
      <div class="container">
        <div class="row justify-content-center">

          <div class="col-lg-4 col-12 mb-4 mb-lg-0">
            <div class="custom-block bg-white shadow-lg">
              <a href="topics-detail.html">
                <div class="d-flex">
                  <div>
                    <h5 class="mb-2">IOTMCC</h5>
                    <p class="mb-0"> sebuah sistem pintar yang menggunakan teknologi internet (IoT) untuk memantau dan
                      mengatur kondisi iklim (seperti suhu, kelembaban, dan ventilasi) di banyak zona atau ruangan yang
                      berbeda
                      secara otomatis dan terpusat.</p>
                  </div>

                  <span class="badge bg-design rounded-pill ms-auto"></span>
                </div>
                <img src="{{ asset('assets/user/images/vanili-base.webp') }}" class="custom-block-image img-fluid"
                  alt="">
              </a>
            </div>
          </div>

          <div class="col-lg-6 col-12">
            <div class="custom-block custom-block-overlay">
              <div class="d-flex flex-column h-100">
                <img src="images/businesswoman-using-tablet-analysis.jpg" class="custom-block-image img-fluid" alt="">

                <div class="custom-block-overlay-text d-flex">
                  <div>
                    <h5 class="text-white mb-2">Vanili</h5>
                    <p class="text-white">Vanili (Vanilla planifolia) adalah tanaman rempah yang sangat berharga,
                      sering dijuluki sebagai "Emas Hijau" atau komoditas "berlian hitam" karena nilai ekonominya yang
                      tinggi.</p>

                    <a href="topics-detail.html" class="btn custom-btn mt-2 mt-lg-3">Learn More</a>
                  </div>

                  <span class="badge bg-finance rounded-pill ms-auto"></span>
                </div>

                <div class="social-share d-flex">
                  <p class="text-white me-4">Share:</p>

                  <ul class="social-icon">
                    <li class="social-icon-item">
                      <a href="#" class="social-icon-link bi-twitter"></a>
                    </li>

                    <li class="social-icon-item">
                      <a href="#" class="social-icon-link bi-facebook"></a>
                    </li>
                </div>

                <div class="section-overlay"></div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- Galeri Section -->
    <section class="explore-section section-padding" id="section_2">
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

    <!-- Timeline Section -->
    <section class="timeline-section section-padding" id="section_3">
      <div class="section-overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <h2 class="text-white mb-4">Bagaimana IoT MCC Berjalan?</h2>
          </div>
          <div class="col-lg-10 col-12 mx-auto">
            <div class="timeline-container">
              <ul class="vertical-scrollable-timeline" id="vertical-scrollable-timeline">
                <div class="list-progress">
                  <div class="inner"></div>
                </div>
                <li>
                  <h4 class="text-white mb-3">Perancangan Sistem IoT</h4>
                  <p class="text-white">
                    Tim IoT MCC memulai dengan merancang sistem yang mampu memantau dan mengontrol berbagai aspek
                    budidaya vanili secara otomatis.
                  </p>
                  <div class="icon-holder">
                    <i class="bi-cpu"></i>
                  </div>
                </li>
                <li>
                  <h4 class="text-white mb-3">Pengujian dan Implementasi</h4>
                  <p class="text-white">
                    Setelah perancangan, sistem diuji di lapangan untuk memastikan sensor dan aktuator bekerja sesuai
                    kebutuhan budidaya vanili.
                  </p>
                  <div class="icon-holder">
                    <i class="bi-gear"></i>
                  </div>
                </li>
                <li>
                  <h4 class="text-white mb-3">Pemantauan dan Analisis Data</h4>
                  <p class="text-white">
                    Data dari sensor dikumpulkan dan dianalisis untuk meningkatkan efisiensi sistem serta membantu
                    petani dalam pengambilan keputusan.
                  </p>
                  <div class="icon-holder">
                    <i class="bi-bar-chart"></i>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- faq -->
    {{-- <section class="faq-section section-padding" id="section_4">
      <div class="container">
        <div class="row">

          <div class="col-lg-6 col-12">
            <h2 class="mb-4">Frequently Asked Questions</h2>
          </div>

          <div class="clearfix"></div>

          <div class="col-lg-5 col-12">
            <img src="images/faq_graphic.jpg" class="img-fluid" alt="FAQs">
          </div>

          <div class="col-lg-6 col-12 m-auto">
            <div class="accordion" id="accordionExample">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    What is Topic Listing?
                  </button>
                </h2>

                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                  data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    Topic Listing is free Bootstrap 5 CSS template. <strong>You are not allowed to redistribute this
                      template</strong> on any other template collection website without our permission. Please contact
                    TemplateMo for more detail. Thank you.
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    How to find a topic?
                  </button>
                </h2>

                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                  data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    You can search on Google with <strong>keywords</strong> such as templatemo portfolio, templatemo
                    one-page layouts, photography, digital marketing, etc.
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Does it need to paid?
                  </button>
                </h2>

                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                  data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    You can modify any of this with custom CSS or overriding our default variables. It's also worth
                    noting that just about any HTML can go within the <code>.accordion-body</code>, though the
                    transition does limit overflow.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
 --}}

 <!-- lokasi section -->
    <section class="lokasi-section section-padding section-bg" id="section_5">
      <div class="container">
        <div class="row">

          <div class="col-lg-12 col-12 text-center">
            <h2 class="mb-5">Lokasi</h2>
          </div>

          <div class="col-lg-5 col-12 mb-4 mb-lg-0">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.73643959526!2d113.71969197484798!3d-8.128293291901382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695007abe851f%3A0x984e5c4f84e4956b!2sVanili%20Agrofilia%20Permata!5e0!3m2!1sid!2sid!4v1759897839593!5m2!1sid!2sid"
              width="100%" height="250" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">\></iframe>
          </div>

          <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg- mb-md-0 ms-auto">
            <h4 class="mb-3">Kebun</h4>
            <p>Area Sawah/Kulon, Baratan, Kec. Patrang, Kabupaten Jember, Jawa Timur</p>
            <hr>
            <p class="d-flex align-items-center mb-1">
              <span class="me-2">Phone</span>
              <a href="tel: 0815 9200022" class="site-footer-link">
                0815 9200022
              </a>
            </p>

            <p class="d-flex align-items-center">
              <span class="me-2">Email</span>

              <a href="mailto:info@permataindonesia.com" class="site-footer-link">
                info@permataindonesia.com
              </a>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 col-12 mx-auto">
            <h4 class="mb-3">Head Office</h4>
            <p>Jl. Kyai Maja No.7a, RT.4/RW.1, Grogol Sel., Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12130</p>
            <hr>
            <p class="d-flex align-items-center mb-1">
              <span class="me-2">Phone</span>
              <a href="tel: 0815 9200022" class="site-footer-link">
                0815 9200022
              </a>
            </p>

            <p class="d-flex align-items-center">
              <span class="me-2">Email</span>

              <a href="mailto:info@permataindonesia.com" class="site-footer-link">
                info@permataindonesia.com
              </a>
            </p>
          </div>

        </div>
      </div>
    </section>
  </main>

  <!-- Footer section -->
  <footer class="site-footer section-padding">
    <div class="container">
      <div class="row">

        <div class="col-lg-3 col-12 mb-4 pb-2">
          <a class="navbar-brand mb-2" href="">
            <i class="bi-back"></i>
            <span>Topic</span>
          </a>
        </div>

        <div class="col-lg-3 col-md-4 col-6">
          <h6 class="site-footer-title mb-3">Resources</h6>

          <ul class="site-footer-links">
            <li class="site-footer-link-item">
              <a href="#" class="site-footer-link">Home</a>
            </li>

             <li class="site-footer-link-item">
              <a href="#" class="site-footer-link">Galeri</a>
            </li>

            <li class="site-footer-link-item">
              <a href="#" class="site-footer-link">How it works</a>
            </li>

            {{-- <li class="site-footer-link-item">
              <a href="#" class="site-footer-link">FAQs</a>
            </li> --}}

            <li class="site-footer-link-item">
              <a href="#" class="site-footer-link">Lokasi</a>
            </li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-4 col-6 mb-4 mb-lg-0">
          <h6 class="site-footer-title mb-3">Information</h6>

          <p class="text-white d-flex mb-1">
            <a href="tel: 305-240-9671" class="site-footer-link">
              305-240-9671
            </a>
          </p>

          <p class="text-white d-flex">
            <a href="mailto:pengaduan@permataindonesia.com" class="site-footer-link">
              pengaduan@permataindonesia.com
            </a>
          </p>
        </div>

        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0 ms-auto">
          <p class="copyright-text mt-lg-5 mt-4">Copyright © 2025 IOTMCC. All rights reserved.
          </p>

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