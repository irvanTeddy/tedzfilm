<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TedzFilm - Katalog & Rekomendasi Film</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #0b0b0f;
            --bg-soft: #14141b;
            --card: #181821;
            --line: rgba(255, 255, 255, .08);
            --accent: #ffb703;
            --accent-2: #fb8500;
            --muted: #9a9aab;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background:
                radial-gradient(900px 400px at 10% -5%, rgba(255, 183, 3, .10), transparent 60%),
                radial-gradient(800px 400px at 95% 0%, rgba(251, 133, 0, .08), transparent 60%),
                var(--bg);
            background-attachment: fixed;
            color: #f1f1f5;
            min-height: 100vh;
        }

        /* ===== Navbar (sticky) ===== */
        .site-nav {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(11, 11, 15, .72);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--line);
            transition: background .25s, box-shadow .25s;
        }

        .site-nav.scrolled {
            background: rgba(11, 11, 15, .92);
            box-shadow: 0 8px 30px rgba(0, 0, 0, .45);
        }

        .brand {
            font-weight: 800;
            letter-spacing: -.5px;
            font-size: 1.5rem;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-icon {
            -webkit-text-fill-color: initial;
        }

        .search-input {
            background: rgba(255, 255, 255, .06) !important;
            border: 1px solid var(--line) !important;
            color: #fff !important;
            border-radius: 999px !important;
            padding-left: 1.1rem;
            min-width: 260px;
        }

        .search-input::placeholder {
            color: var(--muted);
        }

        .search-input:focus {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 .2rem rgba(255, 183, 3, .18) !important;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border: none;
            color: #1a1200;
            border-radius: 999px;
            padding-inline: 1.2rem;
            transition: transform .15s, box-shadow .15s;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: #1a1200;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(255, 183, 3, .3);
        }

        .btn-pill {
            border-radius: 999px;
        }

        .btn-outline-warning {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-outline-warning:hover {
            background: var(--accent);
            color: #1a1200;
        }

        /* ===== Section Title ===== */
        .section-title {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-weight: 700;
            font-size: 1.35rem;
            margin-bottom: 1.25rem;
        }

        .section-title::before {
            content: "";
            width: 5px;
            height: 1.6rem;
            border-radius: 4px;
            background: linear-gradient(var(--accent), var(--accent-2));
        }

        .section-title.gold {
            color: var(--accent);
        }

        /* ===== Movie Card ===== */
        .movie-card {
            position: relative;
            display: block;
            border-radius: 14px;
            overflow: hidden;
            background: var(--card);
            border: 1px solid var(--line);
            color: #fff;
            text-decoration: none;
            height: 100%;
            transition: transform .3s cubic-bezier(.2, .8, .2, 1), box-shadow .3s, border-color .3s;
        }

        .movie-card:hover {
            transform: translateY(-8px);
            border-color: rgba(255, 183, 3, .5);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .6), 0 0 0 1px rgba(255, 183, 3, .25);
            color: #fff;
        }

        .poster-wrap {
            position: relative;
            aspect-ratio: 2 / 3;
            overflow: hidden;
            background: #22222d;
        }

        .poster-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .movie-card:hover .poster-img {
            transform: scale(1.08);
        }

        .poster-wrap::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(11, 11, 15, .85) 0%, transparent 45%);
            opacity: .75;
            transition: opacity .3s;
            pointer-events: none;
        }

        .movie-card:hover .poster-wrap::after {
            opacity: 1;
        }

        .no-poster {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: .4rem;
            color: var(--muted);
            font-size: .85rem;
        }

        .no-poster span.ico {
            font-size: 2rem;
        }

        .rating-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            background: rgba(0, 0, 0, .72);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 183, 3, .5);
            color: var(--accent);
            font-weight: 700;
            font-size: .78rem;
            padding: .25rem .55rem;
            border-radius: 999px;
        }

        .play-btn {
            position: absolute;
            z-index: 2;
            left: 50%;
            top: 50%;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #1a1200;
            font-size: 1.2rem;
            transform: translate(-50%, -30%) scale(.6);
            opacity: 0;
            transition: all .3s;
        }

        .movie-card:hover .play-btn {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .card-info {
            padding: .75rem .8rem .9rem;
        }

        .movie-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: .95rem;
            font-weight: 600;
            margin: 0 0 .15rem;
        }

        .movie-year {
            color: var(--muted);
            font-size: .8rem;
        }

        /* ===== Rekomendasi highlight ===== */
        .rec-wrap {
            padding: 1.5rem;
            border-radius: 20px;
            background: linear-gradient(145deg, rgba(255, 183, 3, .10), rgba(255, 183, 3, .02) 60%);
            border: 1px solid rgba(255, 183, 3, .18);
        }

        /* ===== Pagination ===== */
        .page-info {
            background: rgba(255, 255, 255, .06);
            border: 1px solid var(--line);
            padding: .35rem .9rem;
            border-radius: 999px;
            color: var(--muted);
            font-size: .85rem;
        }

        /* ===== Alert / Empty ===== */
        .alert-danger {
            background: rgba(220, 53, 69, .15);
            border: 1px solid rgba(220, 53, 69, .4);
            color: #ff9aa5;
            border-radius: 12px;
        }

        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
            color: var(--muted);
            border: 1px dashed var(--line);
            border-radius: 20px;
            background: rgba(255, 255, 255, .02);
        }

        .empty-state .big {
            font-size: 3.5rem;
            margin-bottom: .5rem;
        }

        /* ===== Footer ===== */
        .site-footer {
            margin-top: 4rem;
            background: var(--bg-soft);
            border-top: 1px solid var(--line);
            color: var(--muted);
        }

        /* ===== Scroll to top ===== */
        #toTop {
            position: fixed;
            right: 20px;
            bottom: 20px;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #1a1200;
            font-size: 1.2rem;
            font-weight: 700;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .5);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all .3s;
            z-index: 1040;
        }

        #toTop.show {
            opacity: 1;
            visibility: visible;
            transform: none;
        }

        @media (max-width: 991.98px) {
            .search-input {
                min-width: 0;
                width: 100%;
            }

            .site-nav .navbar-collapse {
                padding-bottom: .5rem;
            }
        }

        @media (max-width: 575.98px) {
            .rec-wrap {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>

    <!-- Header Navbar (Sticky) -->
    <nav class="navbar navbar-expand-lg navbar-dark site-nav py-2" id="siteNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 brand m-0" href="{{ route('movies.index') }}">
                <span class="brand-icon">🎬</span> TedzFilm
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarContent">
                <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center ms-auto gap-2">

                    <!-- Form Pencarian -->
                    <form action="{{ route('movies.index') }}" method="GET" class="d-flex gap-2">
                        <input class="form-control search-input" type="search" name="search" placeholder="🔎 Cari judul film..." value="{{ $searchQuery ?? '' }}">
                        <button class="btn btn-warning fw-bold" type="submit">Cari</button>

                        @if(!empty($searchQuery))
                        <a href="{{ route('movies.index') }}" class="btn btn-outline-secondary text-white fw-bold btn-pill d-flex align-items-center justify-content-center" title="Reset Pencarian">
                            ✕
                        </a>
                        @endif
                    </form>

                    <!-- Login / Dashboard -->
                    @auth
                    <a href="{{ route('admin.recommendations') }}" class="btn btn-outline-warning btn-pill fw-bold text-nowrap">
                        ⚙️ Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-pill text-nowrap" title="Login Admin">
                        🔐 Login
                    </a>
                    @endauth

                </div>
            </div>
        </div>
    </nav>

    <main class="container my-4 my-lg-5">

        <!-- Notifikasi Error / Info -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Section 1: Rekomendasi Pilihan Tedz -->
        @if(!empty($customRecommendations) && !$searchQuery && $currentPage === 1)
        <section class="rec-wrap mb-5">
            <h4 class="section-title gold">⭐ Rekomendasi Pilihan Tedz</h4>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 g-lg-4">
                @foreach($customRecommendations as $rec)
                <div class="col">
                    <a href="{{ route('movies.show', $rec['id']) }}" class="movie-card shadow-sm">
                        <div class="poster-wrap">
                            <span class="rating-badge">★ {{ number_format($rec['vote_average'] ?? 0, 1) }}</span>
                            @if(!empty($rec['poster_path']))
                            <img src="https://image.tmdb.org/t/p/w500{{ $rec['poster_path'] }}" class="poster-img" loading="lazy" alt="{{ $rec['title'] ?? 'Poster' }}">
                            @else
                            <div class="no-poster"><span class="ico">🎞️</span><span>No Poster</span></div>
                            @endif
                            <span class="play-btn">▶</span>
                        </div>
                        <div class="card-info">
                            <h6 class="movie-title" title="{{ $rec['title'] ?? 'Tanpa Judul' }}">{{ $rec['title'] ?? 'Tanpa Judul' }}</h6>
                            <span class="movie-year">{{ isset($rec['release_date']) ? substr($rec['release_date'], 0, 4) : '—' }}</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Section 2: Daftar Film Utama -->
        <h4 class="section-title">
            @if($searchQuery)
            🔍 Hasil Pencarian: "{{ $searchQuery }}"
            @else
            🔥 Film Populer Hari Ini
            @endif
        </h4>

        @if(!empty($movies) && count($movies) > 0)
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 g-lg-4">
            @foreach($movies as $movie)
            <div class="col">
                <a href="{{ route('movies.show', $movie['id']) }}" class="movie-card shadow-sm">
                    <div class="poster-wrap">
                        <span class="rating-badge">★ {{ number_format($movie['vote_average'] ?? 0, 1) }}</span>
                        @if(!empty($movie['poster_path']))
                        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" class="poster-img" loading="lazy" alt="{{ $movie['title'] ?? 'Poster' }}">
                        @else
                        <div class="no-poster"><span class="ico">🎞️</span><span>No Poster</span></div>
                        @endif
                        <span class="play-btn">▶</span>
                    </div>
                    <div class="card-info">
                        <h6 class="movie-title" title="{{ $movie['title'] ?? 'Tanpa Judul' }}">{{ $movie['title'] ?? 'Tanpa Judul' }}</h6>
                        <span class="movie-year">{{ isset($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '—' }}</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex flex-wrap justify-content-center align-items-center my-5 gap-2">
            @if($currentPage > 1)
            <a href="{{ route('movies.index', ['page' => $currentPage - 1, 'search' => $searchQuery]) }}" class="btn btn-outline-warning btn-pill btn-sm px-3">&laquo; Sebelumnya</a>
            @endif

            <span class="page-info">Halaman {{ $currentPage }} dari {{ $totalPages }}</span>

            @if($currentPage < $totalPages)
                <a href="{{ route('movies.index', ['page' => $currentPage + 1, 'search' => $searchQuery]) }}" class="btn btn-outline-warning btn-pill btn-sm px-3">Selanjutnya &raquo;</a>
                @endif
        </div>

        @else
        <div class="empty-state">
            <div class="big">🍿</div>
            <p class="mb-3">Film tidak ditemukan atau terjadi masalah koneksi ke server API.</p>
            @if(!empty($searchQuery))
            <a href="{{ route('movies.index') }}" class="btn btn-warning btn-sm fw-bold">Kembali ke Beranda</a>
            @endif
        </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="site-footer py-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div>
                <span class="fw-bold brand fs-6">🎬 TedzFilm</span>
                <span class="ms-1">&copy; {{ date('Y') }} - All rights reserved.</span>
            </div>
            <div class="d-flex align-items-center gap-2 small">
                <span>Powered by</span>
                <svg width="80" height="12" viewBox="0 0 187.32 12.39" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.11 0H0v12.39h23.11V0z" fill="#0d253f" />
                    <path d="M68.51 0H45.4v12.39h23.11V0z" fill="#01b4e4" />
                    <path d="M113.92 0H90.81v12.39h23.11V0z" fill="#90cea1" />
                </svg>
            </div>
        </div>
    </footer>

    <button id="toTop" aria-label="Kembali ke atas" title="Kembali ke atas">↑</button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            var nav = document.getElementById('siteNav');
            var toTop = document.getElementById('toTop');

            function onScroll() {
                var y = window.scrollY || document.documentElement.scrollTop;
                nav.classList.toggle('scrolled', y > 10);
                toTop.classList.toggle('show', y > 400);
            }
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            onScroll();

            toTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        })();
    </script>
</body>

</html>