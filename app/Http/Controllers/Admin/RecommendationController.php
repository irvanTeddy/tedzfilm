<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RecommendationController extends Controller
{
    // 1. Tampilkan Form Login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.recommendations');
        }
        return view('admin.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.recommendations'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // 4. Dashboard dengan Fitur Cari Judul Film
    public function index(Request $request)
    {
        $token = config('services.tmdb.token') ?? env('TMDB_TOKEN');
        $searchQuery = $request->input('search');
        $searchResults = [];

        // Jika admin mengetik judul film di pencarian
        if ($searchQuery) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->withToken($token)
                    ->get('https://api.themoviedb.org/3/search/movie', [
                        'query' => $searchQuery,
                    ]);

                if ($response->successful()) {
                    $searchResults = $response->json()['results'] ?? [];
                }
            } catch (\Exception $e) {
                $searchResults = [];
            }
        }

        $favorites = Favorite::latest()->get();

        return view('admin.recommendations', compact('favorites', 'searchResults', 'searchQuery'));
    }

    // 5. Simpan Film Pilihan dari Hasil Pencarian atau ID
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|integer|unique:favorites,movie_id',
            'title' => 'nullable|string',
            'poster_path' => 'nullable|string',
        ], [
            'movie_id.unique' => 'Film ini sudah ada di daftar rekomendasi!',
        ]);

        Favorite::create([
            'movie_id'    => $request->movie_id,
            'title'       => $request->title ?? ('Movie ID: ' . $request->movie_id),
            'poster_path' => $request->poster_path ?? null,
        ]);

        return redirect()->route('admin.recommendations')->with('success', 'Film "' . ($request->title ?? $request->movie_id) . '" berhasil ditambahkan ke rekomendasi!');
    }

    // 6. Hapus Film dari Rekomendasi
    public function destroy($id)
    {
        Favorite::destroy($id);
        return back()->with('success', 'Film berhasil dihapus dari rekomendasi.');
    }
}
