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
        ]);

        $user->update($request->only(['name', 'phone']));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePin(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'current_pin' => 'required|string|size:4',
            'new_pin' => 'required|string|size:4|digits:4',
            'new_pin_confirmation' => 'required|same:new_pin',
        ]);

        if (! Hash::check($request->current_pin, $user->pin_hash)) {
            return back()->withErrors(['current_pin' => 'PIN lama salah.']);
        }

        $user->update([
            'pin_hash' => Hash::make($request->new_pin),
            'pin_changed_at' => now(),
        ]);

        return back()->with('success', 'PIN berhasil diperbarui.');
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
