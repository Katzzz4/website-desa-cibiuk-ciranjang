<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /** Peran yang tersedia beserta keterangannya */
    public const PERAN = [
        'superadmin' => ['Super Admin', 'Akses penuh, termasuk mengelola akun pengguna.'],
        'admin'      => ['Admin Desa', 'Mengelola laporan warga serta seluruh isi situs desa.'],
        'kadus'      => ['Kepala Dusun', 'Hanya menangani laporan warga di dusunnya sendiri. Tidak dapat mengubah isi situs.'],
        'warga'      => ['Tanpa Akses', 'Akun nonaktif, tidak dapat masuk ke dashboard.'],
    ];

    public function index()
    {
        $pengguna = User::with('dusun')
            ->orderByRaw("FIELD(role, 'superadmin', 'admin', 'kadus', 'warga')")
            ->orderBy('name')
            ->paginate(20);

        return view('admin.user.index', compact('pengguna'));
    }

    public function create()
    {
        $dusun = Dusun::orderBy('nama')->get();

        return view('admin.user.create', compact('dusun'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(array_keys(self::PERAN))],
            'no_hp' => 'nullable|string|max:20',
            'dusun_id' => 'nullable|exists:dusun,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'no_hp' => $validated['no_hp'] ?? null,
            // dusun hanya relevan untuk kepala dusun
            'dusun_id' => $validated['role'] === 'kadus' ? ($validated['dusun_id'] ?? null) : null,
            'email_verified_at' => now(), // dibuat oleh admin, tidak perlu verifikasi email
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', "Akun untuk {$validated['name']} berhasil dibuat.");
    }

    public function edit(User $user)
    {
        $dusun = Dusun::orderBy('nama')->get();

        return view('admin.user.edit', compact('user', 'dusun'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(array_keys(self::PERAN))],
            'no_hp' => 'nullable|string|max:20',
            'dusun_id' => 'nullable|exists:dusun,id',
        ]);

        // Pengaman: jangan sampai tidak tersisa satu pun Super Admin
        $turunDariSuperadmin = $user->role === 'superadmin' && $validated['role'] !== 'superadmin';
        if ($turunDariSuperadmin && User::where('role', 'superadmin')->count() <= 1) {
            return back()->withInput()->withErrors([
                'role' => 'Peran tidak dapat diubah karena ini satu-satunya akun Super Admin yang tersisa.',
            ]);
        }

        // Pengaman: jangan sampai admin menurunkan perannya sendiri lalu terkunci
        if ($user->id === $request->user()->id && $validated['role'] !== 'superadmin') {
            return back()->withInput()->withErrors([
                'role' => 'Anda tidak dapat menurunkan peran akun Anda sendiri.',
            ]);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->no_hp = $validated['no_hp'] ?? null;
        $user->dusun_id = $validated['role'] === 'kadus' ? ($validated['dusun_id'] ?? null) : null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.user.index')
            ->with('success', "Akun {$user->name} berhasil diperbarui.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['hapus' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        if ($user->role === 'superadmin' && User::where('role', 'superadmin')->count() <= 1) {
            return back()->withErrors(['hapus' => 'Akun Super Admin terakhir tidak dapat dihapus.']);
        }

        $nama = $user->name;
        $user->delete();

        return back()->with('success', "Akun {$nama} berhasil dihapus.");
    }
}