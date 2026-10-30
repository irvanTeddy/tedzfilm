<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $token = config('services.tmdb.token') ?? env('TMDB_TOKEN');
        $searchQuery = $request->input('search');
        $currentPage = (int) $request->input('page', 1);

        $customRecommendations = [];
        $movies = [];
        $totalPages = 1;

        // 1. Ambil Rekomendasi Pilihan Tedz dari Database Local (Hanya Halaman 1 & Tanpa Pencarian)
        if (!$searchQuery && $currentPage === 1) {
            $favorites = Favorite::all();

            foreach ($favorites as $fav) {
                // Template dasar data lokal jika cURL gagal
                $movieItem = [
                    'id'           => $fav->movie_id,
                    'title'        => $fav->title,
                    'poster_path'  => $fav->poster_path,
                    'vote_average' => 8.5,
                    'release_date' => 'Handpicked',
                ];

                try {
                    $recResponse = Http::withoutVerifying()
                        ->timeout(5)
                        ->withToken($token)
                        ->get("https://api.themoviedb.org/3/movie/{$fav->movie_id}", [
                            'language' => 'id-ID',
                        ]);

                    if ($recResponse->successful()) {
                        $movieItem = $recResponse->json();
                    }
                } catch (\Exception $e) {
                    // Jika cURL ke API TMDB reset/timeout, gunakan data dari database lokal
                }

                $customRecommendations[] = $movieItem;
            }
        }

        // 2. Ambil Daftar Film Utama (Search / Popular) dari API TMDB
        try {
            if ($searchQuery) {
                $response = Http::withoutVerifying()
                    ->timeout(15)
                    ->retry(3, 300)
                    ->withToken($token)
                    ->get('https://api.themoviedb.org/3/search/movie', [
                        'query'    => $searchQuery,
                        'page'     => $currentPage,
                        'language' => 'id-ID',
                    ]);
            } else {
                $response = Http::withoutVerifying()
                    ->timeout(15)
                    ->retry(3, 300)
                    ->withToken($token)
                    ->get('https://api.themoviedb.org/3/movie/popular', [
                        'page'     => $currentPage,
                        'language' => 'id-ID',
                    ]);
            }

            if ($response && $response->successful()) {
                $data = $response->json();
                $movies = $data['results'] ?? [];
                $totalPages = min($data['total_pages'] ?? 1, 500);
            }
        } catch (\Exception $e) {
            // Jika koneksi cURL API terputus, siapkan fallback daftar film populer agar halaman utama tidak kosong
            if (empty($movies) && !$searchQuery) {
                $movies = [
                    ['id' => 557, 'title' => 'Spider-Man 2', 'poster_path' => '/olA37s1N.jpg', 'vote_average' => 7.3, 'release_date' => '2004-06-25'],
                    ['id' => 105, 'title' => 'Back to the Future', 'poster_path' => '/fT24R9320dI2L7S013Y1Gf.jpg', 'vote_average' => 8.3, 'release_date' => '1985-07-03'],
                    ['id' => 120, 'title' => 'The Lord of the Rings: The Fellowship of the Ring', 'poster_path' => '/6kW.jpg', 'vote_average' => 8.4, 'release_date' => '2001-12-18'],
                    ['id' => 9799, 'title' => 'The Fast and the Furious', 'poster_path' => '/g4.jpg', 'vote_average' => 6.9, 'release_date' => '2001-06-22'],
                ];
            }
        }

        return view('movies.index', compact(
            'movies',
            'searchQuery',
            'currentPage',
            'totalPages',
            'customRecommendations'
        ));
    }

    public function show($id)
    {
        $token = config('services.tmdb.token') ?? env('TMDB_TOKEN');

        try {
            // 1. Ambil Detail Film Utama dalam Bahasa Indonesia
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->retry(3, 300)
                ->withToken($token)
                ->get("https://api.themoviedb.org/3/movie/{$id}", [
                    'language' => 'id-ID',
                ]);

            if ($response->failed()) {
                abort(404, 'Film tidak ditemukan.');
            }

            $movie = $response->json();

            // Jika sinopsis (overview) dalam Bahasa Indonesia kosong, ambil fallback Bahasa Inggris
            if (empty($movie['overview'])) {
                try {
                    $enResponse = Http::withoutVerifying()
                        ->timeout(5)
                        ->withToken($token)
                        ->get("https://api.themoviedb.org/3/movie/{$id}", [
                            'language' => 'en-US',
                        ]);
                    if ($enResponse->successful()) {
                        $movie['overview'] = $enResponse->json()['overview'] ?? 'Sinopsis tidak tersedia dalam Bahasa Indonesia.';
                    }
                } catch (\Exception $e) {
                    $movie['overview'] = 'Sinopsis tidak tersedia dalam Bahasa Indonesia.';
                }
            }

            $trailerKey = null;

            // 2. Ambil Trailer YouTube Terpisah
            try {
                $videoResponse = Http::withoutVerifying()
                    ->timeout(8)
                    ->withToken($token)
                    ->get("https://api.themoviedb.org/3/movie/{$id}/videos", [
                        'language' => 'id-ID',
                    ]);

                $videos = $videoResponse->json()['results'] ?? [];

                // Jika tidak ada trailer versi id-ID, coba ambil dari en-US
                if (empty($videos)) {
                    $videoResponseEn = Http::withoutVerifying()
                        ->timeout(8)
                        ->withToken($token)
                        ->get("https://api.themoviedb.org/3/movie/{$id}/videos", [
                            'language' => 'en-US',
                        ]);
                    $videos = $videoResponseEn->json()['results'] ?? [];
                }

                foreach ($videos as $video) {
                    if (isset($video['type'], $video['site']) && $video['type'] === 'Trailer' && $video['site'] === 'YouTube') {
                        $trailerKey = $video['key'];
                        break;
                    }
                }
            } catch (\Exception $e) {
                // Biarkan trailerKey null jika gagal agar halaman detail tetap tampil
            }

            return view('movies.show', compact('movie', 'trailerKey'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data film dari TMDB. Silakan coba beberapa saat lagi.');
        }
    }
}
