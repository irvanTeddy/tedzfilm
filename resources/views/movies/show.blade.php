<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] ?? 'Detail Film' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #141414;
            color: white;
        }

        .hero-banner {
            background-size: cover;
            background-position: center;
            position: relative;
            padding: 80px 0;
            border-radius: 15px;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            border-radius: 15px;
        }

        .poster-detail {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <!-- Tombol Kembali -->
        <a href="{{ route('movies.index') }}" class="btn btn-outline-warning mb-4" onclick="showLoading()">← Kembali ke Katalog</a>
        <!-- Banner & Informasi Film -->
        <div class="hero-banner shadow" style="background-image: url('https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] ?? '' }}');">
            <div class="hero-overlay"></div>

            <div class="row position-relative p-4 p-md-5 align-items-center">
                <!-- Poster -->
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    @if(!empty($movie['poster_path']))
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" class="img-fluid poster-detail" alt="{{ $movie['title'] ?? '' }}">
                    @else
                    <img src="https://via.placeholder.com/500x750?text=No+Image" class="img-fluid poster-detail" alt="No Image">
                    @endif
                </div>

                <!-- Detail Teks -->
                <div class="col-md-8">
                    <h1 class="fw-bold text-warning display-5">{{ $movie['title'] ?? 'Judul Tidak Ada' }}</h1>
                    <p class="text-muted fst-italic fs-5">{{ $movie['tagline'] ?? '' }}</p>

                    <!-- Badges -->
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark me-2">⭐ {{ number_format($movie['vote_average'] ?? 0, 1) }} / 10</span>
                        <span class="badge bg-secondary me-2">⏱️ {{ $movie['runtime'] ?? 0 }} Menit</span>
                        <span class="badge bg-info text-dark">📅 {{ $movie['release_date'] ?? 'N/A' }}</span>
                    </div>

                    <!-- Genre -->
                    <div class="mb-4">
                        <strong>Genre: </strong>
                        @if(!empty($movie['genres']))
                        @foreach($movie['genres'] as $genre)
                        <span class="badge bg-outline-light border me-1">{{ $genre['name'] }}</span>
                        @endforeach
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </div>

                    <!-- Sinopsis -->
                    <h5 class="fw-bold text-warning">Sinopsis</h5>
                    <p class="fs-6 text-light mb-4" style="line-height: 1.8;">
                        {{ !empty($movie['overview']) ? $movie['overview'] : 'Tidak ada sinopsis tersedia untuk film ini.' }}
                    </p>

                    <!-- Tombol Tonton Trailer -->
                    @if($trailerKey)
                    <button type="button" class="btn btn-danger btn-lg fw-bold shadow" data-bs-toggle="modal" data-bs-target="#trailerModal">
                        ▶️ Tonton Trailer
                    </button>
                    @else
                    <button class="btn btn-secondary btn-lg" disabled>Trailer Tidak Tersedia</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pop-Up YouTube Trailer -->
    @if($trailerKey)
    <div class="modal fade" id="trailerModal" tabindex="-1" aria-labelledby="trailerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-warning" id="trailerModalLabel">🎬 Trailer: {{ $movie['title'] }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        <iframe id="trailerFrame" src="https://www.youtube.com/embed/{{ $trailerKey }}" title="YouTube video player" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bootstrap JS (Diperlukan agar Modal Pop-Up Bekerja) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script untuk mematikan suara video otomatis ketika modal ditutup -->
    <script>
        const trailerModal = document.getElementById('trailerModal');
        if (trailerModal) {
            trailerModal.addEventListener('hidden.bs.modal', function() {
                const iframe = document.getElementById('trailerFrame');
                if (iframe) {
                    const iframeSrc = iframe.src;
                    iframe.src = iframeSrc; // Reset src iframe supaya video berhenti diputar
                }
            });
        }
    </script>
</body>

</html>