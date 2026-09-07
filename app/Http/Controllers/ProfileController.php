<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('owner.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
        ]);

        $user->update($request->only(['name', 'phone']));

        return back()->with('success', 'Informasi profil Anda berhasil diperbarui.');
    }

    public function changePin(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'current_pin' => 'required|string|size:4',
            'new_pin' => 'required|string|size:4|digits:4',
            'new_pin_confirmation' => 'required|same:new_pin',
        ], [
            'current_pin.required' => 'PIN saat ini wajib diisi.',
            'current_pin.size' => 'PIN saat ini harus terdiri dari 4 digit angka.',
            'new_pin.required' => 'PIN baru wajib diisi.',
            'new_pin.size' => 'PIN baru harus terdiri dari 4 digit angka.',
            'new_pin.digits' => 'PIN baru harus berupa 4 digit angka.',
            'new_pin_confirmation.required' => 'Konfirmasi PIN baru wajib diisi.',
            'new_pin_confirmation.same' => 'Konfirmasi PIN baru tidak cocok dengan PIN baru.',
        ]);

        if (! Hash::check($request->current_pin, $user->pin_hash)) {
            return back()->withErrors(['current_pin' => 'PIN saat ini yang Anda masukkan salah.']);
        }

        $user->update([
            'pin_hash' => Hash::make($request->new_pin),
            'pin_changed_at' => now(),
        ]);

        return back()->with('success', 'PIN berhasil diubah! Gunakan PIN baru Anda untuk aktivitas selanjutnya.');
    }

    public function karyawanProfile()
    {
        $user = auth()->user();

        return view('karyawan.profile.index', compact('user'));
    }

    public function karyawanUpdate(Request $request)
    {
        return $this->update($request);
    }

    public function karyawanChangePin(Request $request)
    {
        return $this->changePin($request);
    }
}
