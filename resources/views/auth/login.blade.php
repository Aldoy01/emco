<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun - EMKO Gencontrol Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/emko.css') }}">
    <style>
        body{font-family:Inter,Arial,sans-serif;background:linear-gradient(135deg,#f6f9fb,#eef7f7);min-height:100vh;display:grid;place-items:center;margin:0;padding:24px;color:#172033}.login-wrap{width:100%;max-width:440px}.login-brand{display:flex;align-items:center;justify-content:center;margin-bottom:18px}.login-logo{display:block;width:min(100%,340px);height:auto;object-fit:contain}.login-card{background:#fff;border:1px solid #d8dee8;border-radius:14px;padding:28px;box-shadow:0 22px 52px rgba(16,36,63,.12)}.login-card h1{font-size:26px;margin:0 0 8px}.login-card p{margin-top:0}.form-label{display:block;margin-bottom:8px;font-size:12px;font-weight:900;text-transform:uppercase;color:#667085}.form-control{width:100%;border:1px solid #d8dee8;border-radius:10px;padding:12px 14px;font:inherit}.field{margin-bottom:16px}.btn-login{width:100%;min-height:44px;border:0;border-radius:10px;background:linear-gradient(135deg,#f2c15d,#c99a2e);color:#172033;font-weight:900;cursor:pointer}.auth-links{display:grid;gap:10px;margin-top:16px;text-align:center;font-size:14px}.auth-links a{font-weight:900;color:#167a7f}.back-link{display:block;margin-top:16px;text-align:center;color:#667085;font-size:13px}.alert{margin-bottom:16px}.check-row{display:flex;align-items:center;gap:8px;margin-bottom:16px;color:#667085;font-size:14px}.check-row input{width:auto}
    </style>
</head>
<body>
    <div class="login-wrap">
        <div class="login-brand">
            <img class="login-logo" src="{{ asset('images/emko-partnership.png') }}" alt="EMKO Partnership by Tramatekid">
        </div>
        <div class="login-card">
            <h1>Masuk Akun</h1>
            <p>Masuk untuk melanjutkan checkout atau membuka dashboard admin.</p>

            @if($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="field">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <label class="check-row"><input type="checkbox" name="remember"> Ingat saya</label>
                <button type="submit" class="btn-login">Masuk</button>
            </form>
            <div class="auth-links">
                <span>Belum punya akun pembeli? <a href="{{ route('register') }}">Daftar dulu</a></span>
                @if (Route::has('password.request'))<a href="{{ route('password.request') }}">Lupa password?</a>@endif
            </div>
        </div>
        <a class="back-link" href="{{ route('home') }}">Kembali ke website</a>
    </div>
</body>
</html>