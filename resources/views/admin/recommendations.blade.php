<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Rekomendasi Film</title>
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

<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-warning mb-0">⭐ Kelola Rekomendasi</h2>
                <small class="text-muted">Masuk sebagai: {{ Auth::user()->name }}</small>
            </div>
            <div>
                <a href="{{ route('movies.index') }}" class="btn btn-outline-light btn-sm me-2">Lihat Web</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Form Cari Judul Film -->
        <div class="card p-3 p-md-4 mb-4">
            <h5 class="fw-bold mb-3 text-warning">Cari & Tambah Film Rekomendasi</h5>
            <form action="{{ route('admin.recommendations') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-9">
                    <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Ketik judul film (contoh: Back to the Future, Spider-Man)..." value="{{ $searchQuery ?? '' }}" required>
                </div>
                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-warning fw-bold w-100">Cari Film</button>
                </div>
            </form>

            <!-- Hasil Pencarian Film -->
            @if(!empty($searchResults) && count($searchResults) > 0)
            <h6 class="fw-bold mt-4 mb-3 text-light">Hasil Pencarian: "{{ $searchQuery }}"</h6>
            <div class="row row-cols-2 row-cols-md-5 g-3">
                @foreach($searchResults as $movie)
                <div class="col">
                    <div class="card h-100 bg-dark text-white border-secondary">
                        @if(!empty($movie['poster_path']))
                        <img src="https://image.tmdb.org/t/p/w200{{ $movie['poster_path'] }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="bg-secondary text-center py-5">No Image</div>
                        @endif
                        <div class="card-body p-2 d-flex flex-column justify-content-between">
                            <h6 class="card-title text-truncate small fw-bold mb-2">{{ $movie['title'] ?? '' }}</h6>
                            <form action="{{ route('admin.recommendations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                                <input type="hidden" name="title" value="{{ $movie['title'] ?? '' }}">
                                <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">
                                <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold style=" font-size: 0.75rem;">+ Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @elseif(isset($searchQuery) && empty($searchResults))
            <p class="text-muted mt-3 mb-0 small">Film dengan judul "{{ $searchQuery }}" tidak ditemukan. Coba ketik kata kunci lain.</p>
            @endif
        </div>

        <!-- Daftar Film Terpasang -->
        <div class="card p-3 p-md-4">
            <h5 class="fw-bold mb-3 text-light">Daftar Rekomendasi Aktif ({{ $favorites->count() }})</h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Poster</th>
                            <th>ID TMDB</th>
                            <th>Judul Film</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($favorites as $fav)
                        <tr>
                            <td>
                                @if($fav->poster_path)
                                <img src="https://image.tmdb.org/t/p/w92{{ $fav->poster_path }}" class="rounded" style="height: 60px; object-fit: cover;">
                                @else
                                <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td><code>{{ $fav->movie_id }}</code></td>
                            <td class="fw-bold">{{ $fav->title ?? 'N/A' }}</td>
                            <td class="text-end">
                                <form action="{{ route('admin.recommendations.destroy', $fav->id) }}" method="POST" onsubmit="return confirm('Hapus film ini dari rekomendasi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada film rekomendasi yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>