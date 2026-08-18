<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna (dengan Pencarian & Filter Role)
     */
    public function index(Request $request)
    {
        $users = User::when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        })
        ->when($request->role, function ($query, $role) {
            $query->where('role', $role);
        })
        ->latest()
        ->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah pengguna
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan data pengguna baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|string|in:admin,staff,staf,user',
            'jabatan'  => 'nullable|string|max:255',
            'status'   => 'nullable|string|in:aktif,nonaktif',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status']   = $request->status ?? 'aktif';

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail pengguna (opsional)
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Menampilkan form edit pengguna
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8', // Boleh kosong jika tidak ingin ubah password
            'role'     => 'required|string|in:admin,staff,staf,user',
            'jabatan'  => 'nullable|string|max:255',
            'status'   => 'required|string|in:aktif,nonaktif',
        ]);

        // Hapus password dari array validasi jika diisi kosong
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Menghapus pengguna
     */
    public function destroy(User $user)
    {
        // Mencegah hapus akun sendiri
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}