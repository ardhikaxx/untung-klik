<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $users = User::where('business_id', $user->business_id)
            ->latest()
            ->paginate(15);

        return view('owner.users.index', compact('users'));
    }

    public function create()
    {
        return view('owner.users.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,karyawan',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'username.required' => 'Username akun wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan oleh pengguna lain.',
            'phone.max' => 'Nomor WhatsApp / telepon maksimal 20 karakter.',
            'role.required' => 'Peran pengguna (admin atau karyawan) wajib dipilih.',
            'role.in' => 'Pilihan peran pengguna tidak valid.',
            'is_active.required' => 'Status keaktifan pengguna wajib ditentukan.',
        ]);

        $defaultPin = '1234';
        $createdUser = User::create([
            'business_id' => $user->business_id,
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $request->phone,
            'role' => $request->role,
            'pin_hash' => Hash::make($defaultPin),
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('owner.users.index')
            ->with('success', "Akun pengguna \"{$createdUser->name}\" ({$createdUser->username}) berhasil didaftarkan. PIN awal login: {$defaultPin}");
    }

    public function edit(User $user)
    {
        if ($user->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        return view('owner.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:owner,admin,karyawan',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'username.required' => 'Username akun wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan oleh pengguna lain.',
            'phone.max' => 'Nomor WhatsApp / telepon maksimal 20 karakter.',
            'role.required' => 'Peran pengguna wajib dipilih.',
            'role.in' => 'Pilihan peran pengguna tidak valid.',
            'is_active.required' => 'Status keaktifan pengguna wajib ditentukan.',
        ]);

        $user->update($request->only(['name', 'username', 'phone', 'role', 'is_active']));

        return redirect()->route('owner.users.index')
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Gagal memproses: Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang aktif digunakan.');
        }

        $userName = $user->name;
        $user->update(['is_active' => false]);

        return redirect()->route('owner.users.index')
            ->with('success', "Akun pengguna \"{$userName}\" berhasil dinonaktifkan.");
    }
}
