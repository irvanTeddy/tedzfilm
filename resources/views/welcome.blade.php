<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎬 Web Katalog Film (API TMDB)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #141414;
            color: white;
        }

        .card {
            background-color: #1f1f1f;
            color: white;
            border: none;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .poster-img {
            height: 350px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="container my-5">

        <!-- Header & Form Pencarian -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold text-warning mb-0">🎬 Movie Central</h1>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <form action="{{ route('movies.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2 bg-dark text-white border-secondary"
                        placeholder="Cari judul film... (misal: Spider-Man)" value="{{ $searchQuery ?? '' }}">
                    <button class="btn btn-warning fw-bold" type="submit">Cari</button>
                    @if($searchQuery)
                    <a href="{{ route('movies.index') }}" class="btn btn-outline-light ms-2">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Judul Dinamis -->
        <h4 class="fw-bold mb-4 text-secondary">
            {{ $searchQuery ? 'Hasil Pencarian: "' . $searchQuery . '"' : '🔥 Film Populer Hari Ini' }}
        </h4>

        <!-- Grid Daftar Film -->
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
            @forelse($movies as $movie)
            <div class="col">
                <div class="card h-100 shadow">
                    @if(!empty($movie['poster_path']))
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" class="card-img-top poster-img" alt="{{ $movie['title'] }}">
                    @else
                    <img src="https://via.placeholder.com/500x750?text=No+Image" class="card-img-top poster-img" alt="No Image">
                    @endif

                    <div class="card-body d-flex flex-column justify-content-between">
                        <h6 class="card-title fw-bold text-truncate">{{ $movie['title'] }}</h6>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="badge bg-warning text-dark">⭐ {{ number_format($movie['vote_average'] ?? 0, 1) }}</span>
                            <small class="text-muted">{{ substr($movie['release_date'] ?? 'N/A', 0, 4) }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Film yang kamu cari tidak ditemukan. Coba ketik judul lain!</p>
            </div>
            @endforelse
        </div>
    </div>

</body>

</html>