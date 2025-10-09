{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password - IoTMCC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/user/css/forgot-password-style.css') }}">
  <script src="https://cdn.lordicon.com/lordicon.js" class="forgot-card items-center"></script>
</head>
<body>
  <div class="forgot-card text-center">
    <h5 class="mb-3">Lupa Kata Sandi?</h5>

    <lord-icon
      src="https://cdn.lordicon.com/rhvddzym.json"trigger="loop"colors="primary:#0ab39c" style="width:80px;height:80px;margin-bottom:1.5rem;">
    </lord-icon>

    <div class="alert alert-warning text-center">
      Masukkan email anda yang terdaftar untuk melakukan reset kata sandi
    </div>

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="text-start mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" placeholder="Masukkan Email" required autofocus>
      </div>

      <button type="submit" class="btn btn-success w-100">Kirim Link Reset Password</button>
    </form>

    <a href="{{ route('login') }}" class="back-link">Kembali ke Login</a>
  </div>

  <div id="particles-js"></div>
  <script src="{{ asset('assets/user/js/particles.js') }}"></script>
  <script>
    particlesJS.load('particles-js', '{{ asset('assets/user/js/particles.json') }}');
  </script>
</body>
</html>
