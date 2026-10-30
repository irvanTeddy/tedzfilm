<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Movie Central</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #141414;
            color: white;
        }

        .card {
            background-color: #1f1f1f;
            border: none;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow-lg style=" width: 100%; max-width: 400px;">
        <h4 class="fw-bold text-warning text-center mb-1">🔐 Admin Login</h4>
        <p class="text-muted text-center small mb-4">Kelola Rekomendasi Tedz</p>

        @if($errors->any())
        <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small">Email</label>
                <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label small">Password</label>
                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
            </div>
            <button type="submit" class="btn btn-warning w-100 fw-bold">Masuk</button>
            <a href="{{ route('movies.index') }}" class="btn btn-link text-secondary w-100 text-decoration-none mt-2 small text-center d-block">&laquo; Kembali ke Utama</a>
        </form>
    </div>
</body>

</html>