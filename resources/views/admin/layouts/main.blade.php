<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Modern Bootstrap 5 Admin Template - Clean, responsive dashboard">
    <meta name="keywords" content="bootstrap, admin, dashboard, template, modern, responsive">
    <meta name="author" content="Bootstrap Admin Template">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Modern Bootstrap Admin Template">
    <meta property="og:description" content="Clean and modern admin dashboard template built with Bootstrap 5">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/admin/favicon-CvUZKS4z.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/admin/favicon-B_cwPWBd.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Title -->
    <title>Agrofilia Permata</title>
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#6366f1">

    <!-- css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/main-2.css') }}">

    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('assets/admin/manifest-DTaoG9pG.json') }}">
  <script type="module" crossorigin src="{{ asset('assets/admin/main-f0Mg-34g.js') }}"></script>
  <link rel="stylesheet" crossorigin href="{{ asset('assets/admin/main-DLfE7m78.css') }}">
</head>

<body data-page="dashboard" class="admin-layout">

    <div class="admin-wrapper" id="admin-wrapper">

        @include('admin.layouts.navbar')

        @include('admin.layouts.sidebar')


         <div class="admin-content">
            @yield('content')
        </div>

    </div>

    <script>
      
    </script>
</body>
</html> 