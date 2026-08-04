<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SR Wood Craft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; background: #f8fafc; }
        .login-container { 
            display: flex; 
            min-height: 100vh; 
            width: 100%;
        }
        
        /* LEFT - FULL IMAGE bgsr.jpeg - 50% DENGAN OVERLAY HITAM */
        .login-image {
            flex: 0 0 50%;
            width: 50%;
            background: url('/images/bgsr.jpeg') center/cover no-repeat;
            min-height: 100vh;
            position: relative;
        }
        
        /* OVERLAY HITAM SAMAR */
        .login-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3); /* 0.3 = 30% kegelapan, bisa diatur */
            /* Untuk lebih gelap: rgba(0, 0, 0, 0.5) */
            /* Untuk lebih terang: rgba(0, 0, 0, 0.15) */
        }

        /* RIGHT - FORM - 50% */
        .login-form {
            flex: 0 0 50%;
            width: 50%;
            display: flex; 
            align-items: center; 
            justify-content: center;
            padding: 40px; 
            background: #fff;
            min-height: 100vh;
        }
        .login-form .wrapper { width: 100%; max-width: 380px; }
        .login-form .wrapper h2 { font-size: 26px; font-weight: 700; color: #1a1a2e; }
        .login-form .wrapper .sub { color: #6b7280; font-size: 14px; margin-bottom: 24px; }
        .login-form .form-group { margin-bottom: 16px; }
        .login-form .form-group label { font-size: 13px; font-weight: 500; color: #374151; display: block; margin-bottom: 4px; }
        .login-form .form-group .input-group {
            border: 2px solid #e5e7eb; border-radius: 10px;
            background: #f8fafc; transition: all 0.3s;
        }
        .login-form .form-group .input-group:focus-within {
            border-color: #1a1a2e; background: #fff;
            box-shadow: 0 0 0 3px rgba(26,26,46,0.1);
        }
        .login-form .form-group .input-group-text {
            background: transparent; border: none; color: #9ca3af; padding: 0 12px 0 16px;
        }
        .login-form .form-group .form-control {
            border: none; background: transparent; padding: 10px 16px 10px 0;
            font-size: 14px; box-shadow: none;
        }
        .login-form .form-group .form-control:focus { box-shadow: none; }
        .login-form .btn-login {
            width: 100%; padding: 12px; background: #1a1a2e; color: #fff;
            border: none; border-radius: 10px; font-weight: 600; font-size: 15px;
            transition: all 0.3s;
        }
        .login-form .btn-login:hover {
            background: #2d2d4a; transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26,26,46,0.3);
        }
        .login-form .error {
            background: #fee2e2; border: 1px solid #fecaca; color: #dc2626;
            padding: 10px 14px; border-radius: 10px; font-size: 14px; margin-bottom: 16px;
        }
        .login-form .default-users {
            background: #f8fafc; border-radius: 10px; padding: 12px 16px;
            margin-top: 16px; border: 1px solid #e5e7eb;
        }
        .login-form .default-users .title { font-size: 11px; font-weight: 600; color: #6b7280; text-transform: uppercase; }
        .login-form .default-users .grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 2px; margin-top: 4px;
        }
        .login-form .default-users .grid code {
            background: #e5e7eb; padding: 2px 8px; border-radius: 4px; font-size: 11px; color: #374151;
        }
        
        @media (max-width: 768px) { 
            .login-image { 
                display: none; 
            }
            .login-form { 
                flex: 0 0 100%;
                width: 100%;
                padding: 20px; 
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- LEFT - FULL IMAGE bgsr.jpeg - 50% DENGAN OVERLAY -->
    <div class="login-image"></div>

    <!-- RIGHT - FORM - 50% -->
    <div class="login-form">
        <div class="wrapper">
            <h2>Selamat Datang</h2>
            <p class="sub">Silakan login untuk mengakses sistem</p>

            @if($errors->any())
                <div class="error"><i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt me-2"></i> Login</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
