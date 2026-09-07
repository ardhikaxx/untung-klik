<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'pin' => 'required|string|size:4',
        ], [
            'username.required' => 'Username wajib diisi.',
            'pin.required' => 'PIN wajib diisi.',
            'pin.size' => 'PIN harus terdiri dari 4 digit angka.',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->pin, $user->pin_hash)) {
            return back()->withErrors([
                'username' => 'Username atau PIN salah.',
            ])->withInput($request->only('username'));
        }

        if (! $user->is_active) {
            return back()->withErrors([
                'username' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->withInput($request->only('username'));
        }

        auth()->login($user);
        $request->session()->regenerate();

        if ($user->isOwner()) {
            return redirect()->route('owner.dashboard')->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return redirect()->route('karyawan.dashboard')->with('success', "Selamat datang kembali, {$user->name}!");
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    public function showForgotPin()
    {
        return view('auth.forgot-pin');
    }

    public function forgotPin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ], [
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
        ]);

        $user = User::where('phone', $request->phone)->where('is_active', true)->first();

        if (! $user) {
            return back()->withErrors([
                'phone' => 'Nomor telepon tidak ditemukan atau akun tidak terdaftar dalam sistem.',
            ])->withInput();
        }

        $token = Str::random(64);
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['phone' => $request->phone],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        return redirect()->route('reset-pin', ['token' => $token])
            ->with('phone', $request->phone)
            ->with('success', 'Nomor telepon terverifikasi. Silakan masukkan PIN baru Anda.');
    }

    public function showResetPin($token)
    {
        $phone = session('phone');
        if (! $phone) {
            return redirect()->route('forgot-pin')->with('error', 'Sesi verifikasi telah berakhir. Silakan masukkan nomor Anda kembali.');
        }

        $record = \DB::table('password_reset_tokens')->where('phone', $phone)->first();
        if (! $record || ! $record->token) {
            return redirect()->route('forgot-pin')->with('error', 'Sesi reset PIN telah kedaluwarsa. Silakan mulai ulang permohonan.');
        }

        return view('auth.reset-pin', ['token' => $token, 'phone' => $phone]);
    }

    public function resetPin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'pin' => ['required', 'string', 'size:4', 'digits:4'],
            'pin_confirmation' => 'required|same:pin',
        ], [
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'pin.required' => 'PIN baru wajib diisi.',
            'pin.size' => 'PIN baru harus terdiri dari 4 digit angka.',
            'pin.digits' => 'PIN baru harus berupa 4 digit angka.',
            'pin_confirmation.required' => 'Konfirmasi PIN wajib diisi.',
            'pin_confirmation.same' => 'Konfirmasi PIN tidak cocok dengan PIN baru.',
        ]);

        $user = User::where('phone', $request->phone)->where('is_active', true)->first();

        if (! $user) {
            return redirect()->route('forgot-pin')->with('error', 'Akun tidak ditemukan.');
        }

        $user->update([
            'pin_hash' => Hash::make($request->pin),
            'pin_changed_at' => now(),
        ]);

        \DB::table('password_reset_tokens')->where('phone', $request->phone)->delete();

        return redirect()->route('login')->with('success', 'PIN berhasil diperbarui. Silakan login dengan PIN baru.');
    }
}
