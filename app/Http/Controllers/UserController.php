<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%')
                  ->orWhere('unit_kerja', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Role::orderBy('display_name')->get();
        $stats = [
            'total'   => User::count(),
            'aktif'   => User::where('is_active', true)->count(),
            'nonaktif'=> User::where('is_active', false)->count(),
        ];

        return view('users.index', compact('users', 'roles', 'stats'));
    }

    public function create()
    {
        $roles = Role::orderBy('display_name')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id'    => 'required|exists:roles,id',
            'name'       => 'required|string|max:255',
            'nip'        => 'nullable|string|max:30|unique:users,nip',
            'email'      => 'required|email|unique:users,email',
            'unit_kerja' => 'nullable|string|max:255',
            'jabatan'    => 'nullable|string|max:255',
            'no_hp'      => 'nullable|string|max:20',
            'password'   => 'required|string|min:8|confirmed',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-user', 'public');
        }

        User::create([
            'role_id'    => $request->role_id,
            'name'       => $request->name,
            'nip'        => $request->nip,
            'email'      => $request->email,
            'unit_kerja' => $request->unit_kerja,
            'jabatan'    => $request->jabatan,
            'no_hp'      => $request->no_hp,
            'password'   => Hash::make($request->password),
            'foto'       => $fotoPath,
            'is_active'  => true,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User "' . $request->name . '" berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load('role');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('display_name')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id'    => 'required|exists:roles,id',
            'name'       => 'required|string|max:255',
            'nip'        => 'nullable|string|max:30|unique:users,nip,' . $user->id,
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'unit_kerja' => 'nullable|string|max:255',
            'jabatan'    => 'nullable|string|max:255',
            'no_hp'      => 'nullable|string|max:20',
            'password'   => 'nullable|string|min:8|confirmed',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'role_id', 'name', 'nip', 'email',
            'unit_kerja', 'jabatan', 'no_hp',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $data['foto'] = $request->file('foto')->store('foto-user', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if ($user->foto) Storage::disk('public')->delete($user->foto);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new \App\Imports\UserImport, $request->file('file'));
            return back()->with('success', 'Data user berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = ['nama', 'email', 'role', 'nip', 'unit_kerja', 'jabatan', 'password'];
        return Excel::download(new \App\Exports\TemplateExport($headings, 'Template User'), 'template-user.xlsx');
    }
}
