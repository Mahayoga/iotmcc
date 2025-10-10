<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta _token="{{ csrf_token() }}" id="meta_token">
  <link rel="stylesheet" href="{{ asset('assets/user/css/login-style.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <title>Selamat Datang - IoTMCC</title>
</head>
<body>
  <div class="container-fluid manual-container-fluid">
    <div class="row justify-content-center align-items-center h-75">
      <div class="col-md-3 bg-white rounded manual-box-login">
        <div class="row p-4">
          <div class="col-md-12 text-center fs-4 fw-bold">
            Selamat Datang !
          </div>
          <div class="col-md-12 text-center fs-6 mb-3 text-secondary">
            Silahkan login untuk melanjutkan.
          </div>
          <div class="col-md-12">
            <div class="my-2">
              <label for="" class="mb-2">Email/Username</label>
              <input id="email" type="text" class="form-control" placeholder="Masukkan username">
            </div>
          </div>
          <div class="col-md-12">
            <div class="my-2">
              <label for="" class="mb-2">Kata Sandi</label>
              <input id="password" type="password" class="form-control" placeholder="Masukkan kata sandi">
            </div>
          </div>
          <div class="col-md-12">
            <div class="my-3">
              <button type="button" class="btn btn-success w-100" onclick="handleLogin()">Login</button>
              <div id="passwordHelpBlock" class="form-text text-end mt-3">
                <a href="" class="link-secondary link-underline link-underline-opacity-0">Lupa kata sandi?</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="particles-js" class="manual-bg-overlay"></div>
  <div id="footer" class="text-secondary text-center p-4 fs-6 w-100">
    © {{ date('Y') }} PT. Permata Indo Sejahtera Crafted with ❤️ by Lab KSI Politeknik Negeri Jember
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="{{ asset('assets/user/js/particles.js') }}"></script>
  <script>
    document.getElementById('particles-js').innerHTML += `
      <div id="manual-overlay"></div>
      <div class="manual-shape">
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
          <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
        </svg>
      </div>
    `;
    particlesJS.load('particles-js', '{{ asset('assets/user/js/particles.json') }}', function() {
      console.log('callback - particles.js config loaded');
      document.getElementsByClassName('particles-js-canvas-el')[0].setAttribute('height', window.innerHeight / 2.5);
      document.getElementById('particles-js').style.position = 'absolute';
      document.getElementById('particles-js').style.zIndex = -1;
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#email').keypress(function(event) {
        if(event.which == 13) {
          let username = document.getElementById('email');
          let password = document.getElementById('password');

          if(username.value == '') {
            password.focus();
          }
          event.preventDefault();
        }
      });
    });

    function test() {
      
    }

    function handleLogin() {
      let formData = new FormData();
      let username = document.getElementById('email').value;
      let password = document.getElementById('password').value;
      let token = document.getElementById('meta_token').getAttribute('_token');

      $.post('{{ route('login') }}', {
        '_token': token,
        'email': username,
        'password': password
      }, function(data, status) {
        if(data.status) {
          Swal.fire({
            title: "Status",
            text: "Login berhasil! Anda akan diarahkan ke dashboard.",
            icon: "success"
          });
          window.location.replace("{{ route('dashboard.index') }}");
        } else {
          Swal.fire({
            title: "Status",
            text: "User tidak ada di database kami!",
            icon: "error"
          });
        }
      });
    }
  </script>
</body>
</html>
