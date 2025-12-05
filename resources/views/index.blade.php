<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-card {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
            background-color: #0b0a0a;
            color: white;
        }
        .login-card label {
            color: #ffffff !important;
        }
        .login-card input::placeholder {
            color: #d4d4d4 !important;
        }
        .logo-img {
            width: 150px;
            display: block;
            margin: 0 auto 20px auto;
        }
        .btn-login {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-card">

        <img src="{{ asset('mindsinmotion.png') }}" class="logo-img" alt="Logo">

        <form action="/login/check" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-login">Login</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ERROR from session('error')
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: '{{ session("error") }}',
            confirmButtonColor: '#d33'
        });
    @endif

    // SUCCESS message from controller
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session("success") }}',
            confirmButtonColor: '#3085d6',
            timer: 2000,
            timerProgressBar: true,
            willClose: () => {
                window.location.href = "/admin/dashboard";  // Auto redirect
            }
        });
    @endif

    // Laravel Validation Errors
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#d33'
        });
    @endif
</script>

</body>
</html>
