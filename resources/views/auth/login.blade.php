{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


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
  <div id="particles-js"></div>

  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="{{ asset('assets/user/js/particles.js') }}"></script>
  <script>
    particlesJS.load('particles-js', '{{ asset('assets/user/js/particles.json') }}', function() {
      console.log('callback - particles.js config loaded');
    });
  </script>
  <script>
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