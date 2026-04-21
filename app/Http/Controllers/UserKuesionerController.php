<?php

namespace App\Http\Controllers;

use App\Models\Kuesioner;
use App\Models\KuesionerJawaban;
use App\Models\KuesionerJawabanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class UserKuesionerController extends Controller
{
    /**
     * Redirect ke kuesioner publik yang sedang aktif (Link Statis)
     */
    public function activeSurvey()
    {
        $active = Kuesioner::where('status', 'aktif')
            ->where('is_public', true)
            ->latest()
            ->first();

        if (!$active) {
            return redirect()->route('user-kuesioner.index')->with('error', 'Tidak ada kuesioner aktif saat ini.');
        }

        return redirect()->route('user-kuesioner.fill', $active);
    }

    public function index()
    {
        $userId = auth()->id();
        $userRole = auth()->check() ? auth()->user()->role->name : null;

        // Ambil kuesioner yang aktif
        $query = Kuesioner::where('status', 'aktif');

        if (!auth()->check()) {
            // Jika tidak login, hanya tampilkan yang bersifat publik
            $query->where('is_public', true);
        } else {
            // Jika login, tampilkan yang sesuai role atau yang publik
            $query->where(function($q) use ($userRole) {
                $q->where('is_public', true)
                  ->orWhereNull('target_role')
                  ->orWhere('target_role', 'all')
                  ->orWhere('target_role', $userRole);
            });
        }

        $kuesioners = $query->withCount(['jawabans' => function($q) use ($userId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->whereRaw('1=0'); // Tidak bisa cek jawaban berdasarkan user_id jika guest
                }
            }])
            ->get();

        // Tambahkan status pengisian via Cookie untuk Guest
        foreach ($kuesioners as $k) {
            $cookieName = 'filled_kuesioner_' . $k->id;
            $k->is_filled_via_cookie = request()->cookie($cookieName) ? true : false;
        }

        return view('kuesioner.user_list', compact('kuesioners'));
    }

    public function fill(Kuesioner $kuesioner)
    {
        if ($kuesioner->status !== 'aktif') {
            return redirect()->route('user-kuesioner.index')->with('error', 'Kuesioner ini tidak aktif.');
        }

        // Proteksi pengisian ganda
        if (auth()->check() && $kuesioner->isFilledBy(auth()->id())) {
            return redirect()->route('user-kuesioner.index')->with('error', 'Anda sudah mengisi kuesioner ini.');
        }
        
        $cookieName = 'filled_kuesioner_' . $kuesioner->id;
        if (request()->cookie($cookieName)) {
            return redirect()->route('user-kuesioner.index')->with('error', 'Anda sudah mengisi kuesioner ini.');
        }

        $kuesioner->load('pertanyaans');
        return view('kuesioner.fill', compact('kuesioner'));
    }

    public function submit(Request $request, Kuesioner $kuesioner)
    {
        // Proteksi pengisian ganda
        if (auth()->check() && $kuesioner->isFilledBy(auth()->id())) {
             return redirect()->route('user-kuesioner.index')->with('error', 'Anda sudah mengirim jawaban.');
        }
        
        $cookieName = 'filled_kuesioner_' . $kuesioner->id;
        if (request()->cookie($cookieName)) {
            return redirect()->route('user-kuesioner.index')->with('error', 'Anda sudah mengirim jawaban.');
        }

        $request->validate([
            'jawaban' => 'required|array',
        ]);

        DB::transaction(function() use ($request, $kuesioner) {
            $jawabanHeader = KuesionerJawaban::create([
                'kuesioner_id' => $kuesioner->id,
                'user_id' => auth()->id(), // NULL jika guest
                'filled_at' => now(),
            ]);

            foreach ($request->jawaban as $pertanyaanId => $val) {
                KuesionerJawabanDetail::create([
                    'jawaban_id' => $jawabanHeader->id,
                    'pertanyaan_id' => $pertanyaanId,
                    'skor' => is_numeric($val) ? $val : null,
                    'jawaban_text' => !is_numeric($val) ? $val : null,
                ]);
            }
        });

        // Set cookie selama 30 hari untuk mencegah pengisian ulang oleh guest
        return redirect()->route('user-kuesioner.index')
            ->with('success', 'Terima kasih! Jawaban Anda telah tersimpan.')
            ->withCookie(cookie($cookieName, 'true', 43200)); 
    }
}
