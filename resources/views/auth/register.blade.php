<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Register - Shop</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-3">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <i class="bx bxs-store-alt bx-lg" style="color:#696cff"></i>
                                </span>
                                <span class="app-brand-text text-body fw-bolder ms-2">Shop</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2">Buat Akun Baru 🚀</h4>
                        <p class="mb-4">Lengkapi data berikut untuk mendaftar.</p>

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <span class="input-group-text cursor-pointer" onclick="togglePassword()"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Daftar Sebagai</label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="customer" {{ old('role')=='customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="supplier" {{ old('role')=='supplier' ? 'selected' : '' }}>Supplier</option>
                                    <option value="mitra" {{ old('role')=='mitra' ? 'selected' : '' }}>Mitra</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success d-grid w-100">Register</button>
                        </form>
                        <p class="text-center mt-3">
                            <span>Sudah punya akun?</span>
                            <a href="{{ route('login') }}">Login di sini</a>
                        </p>
                    </div>
                </div>
                <!-- /Register Card -->
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- Password Toggle -->
    <script>
        function togglePassword() {
            var input = document.getElementById('password');
            var icon = event.target;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = "password";
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
</body>

</html>