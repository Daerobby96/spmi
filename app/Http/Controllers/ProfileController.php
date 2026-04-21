<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil user
     */
    public function show()
    {
        $user = Auth::user();
        $user->load('role');
        return view('profile.show', compact('user'));
    }

    /**
     * Tampilkan form edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profil user
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'       => 'required|string|max:255',
            'nip'        => 'nullable|string|max:30|unique:users,nip,' . $user->id,
            'unit_kerja' => 'nullable|string|max:255',
            'jabatan'    => 'nullable|string|max:255',
            'no_hp'      => 'nullable|string|max:20',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'nip', 'unit_kerja', 'jabatan', 'no_hp']);

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-user', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Tampilkan form pengaturan (ubah password)
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.settings')
            ->with('success', 'Password berhasil diubah.');
    }
}
