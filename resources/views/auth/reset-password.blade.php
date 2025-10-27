<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta _token="{{ csrf_token() }}" id="meta_token">
  <title>Reset Password - IoTMCC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/user/css/login-style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user/css/forgot-password-style.css') }}">
</head>

<body>
  <div class="container-fluid manual-container-fluid">
    <div class="container-fluid manual-container-fluid d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="col-md-5 forgot-box text-center shadow-lg p-4 rounded bg-white" style="z-index: 10;">
    <div class="col-md-12 text-center fs-4 fw-bold mb-3">
      Reset Password
    </div>

    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <lord-icon src="https://cdn.lordicon.com/kthelypq.json" trigger="loop" colors="primary:#0ab39c"
      style="width:80px;height:80px;margin-bottom:1rem;">
    </lord-icon>

    <div class="alert alert-info text-center py-2 mb-3">
      Silakan masukkan kata sandi baru Anda.
    </div>

    @if ($errors->any())
      <div class="alert alert-danger text-center py-2 mb-3">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- FORM RESET PASSWORD --}}
    <form method="POST" action="{{ route('password.store') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <div class="text-start mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email"
          value="{{ old('email', $request->email) }}" placeholder="Masukkan Email" required autofocus>
      </div>

      <div class="text-start mb-3">
        <label for="password" class="form-label">Kata Sandi Baru</label>
        <input type="password" class="form-control" name="password" placeholder="Masukkan Password Baru" required>
      </div>

      <div class="text-start mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
        <input type="password" class="form-control" name="password_confirmation"
          placeholder="Ulangi Password Baru" required>
      </div>

      <button type="submit" class="btn btn-success w-100">
        Reset Password
      </button>
    </form>

    <a href="{{ route('login') }}" class="back-link d-block mt-3">← Kembali ke Login</a>
  </div>
</div>


  <div id="particles-js" class="manual-bg-overlay"></div>
  <div id="footer" class="text-secondary text-center p-4 fs-6 w-100">
    © {{ date('Y') }} PT. Permata Indo Sejahtera — Crafted with ❤️ by Lab KSI Politeknik Negeri Jember
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/user/js/particles.js') }}"></script>

  <script>
    document.getElementById('particles-js').innerHTML += `
      <div id="manual-overlay"></div>
      <div class="manual-shape">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
          <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
        </svg>
      </div>
    `;
    particlesJS.load('particles-js', '{{ asset('assets/user/js/particles.json') }}', function () {
      document.getElementsByClassName('particles-js-canvas-el')[0].setAttribute('height', window.innerHeight / 2.5);
      document.getElementById('particles-js').style.position = 'absolute';
      document.getElementById('particles-js').style.zIndex = -1;
    });
  </script>
</body>

</html>
