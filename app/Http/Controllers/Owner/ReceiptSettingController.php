<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceiptSettingController extends Controller
{
    /**
     * Tampilkan halaman formulir pengaturan bagian nota / struk toko.
     */
    public function index(): View
    {
        $user = auth()->user();
        $business = $user->business;

        if (! $business) {
            $business = Business::firstOrCreate(
                ['owner_id' => $user->id],
                [
                    'name' => 'Galeri E-Bike Uwinfly & NUV',
                    'type' => 'Dealer Resmi Sepeda & Motor Listrik',
                    'phone' => $user->phone ?: '081234567890',
                    'address' => 'Jl. Soekarno-Hatta No. 210, Bandung',
                    'receipt_footer' => 'Terima Kasih Atas Kunjungan Anda!',
                    'receipt_note' => 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.',
                    'is_active' => true,
                ]
            );

            $user->update(['business_id' => $business->id]);
        }

        // Nilai bawaan jika field baru masih kosong
        $business->receipt_footer = $business->receipt_footer ?: 'Terima Kasih Atas Kunjungan Anda!';
        $business->receipt_note = $business->receipt_note ?: 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti nota ini.';

        return view('owner.receipt.index', compact('business'));
    }

    /**
     * Perbarui data identitas toko dan teks nota / struk kasir.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $business = $user->business;

        if (! $business) {
            $business = Business::create([
                'owner_id' => $user->id,
                'name' => $request->input('name', 'Galeri E-Bike Uwinfly & NUV'),
                'is_active' => true,
            ]);
            $user->update(['business_id' => $business->id]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'receipt_footer' => 'nullable|string|max:255',
            'receipt_note' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama usaha / toko pada nota wajib diisi.',
            'name.max' => 'Nama usaha / toko maksimal 255 karakter.',
            'type.max' => 'Slogan atau jenis usaha maksimal 150 karakter.',
            'phone.max' => 'Nomor telepon / WhatsApp toko maksimal 30 karakter.',
            'address.max' => 'Alamat usaha maksimal 500 karakter.',
            'receipt_footer.max' => 'Pesan penutup nota maksimal 255 karakter.',
            'receipt_note.max' => 'Catatan kaki nota maksimal 1000 karakter.',
        ]);

        $business->update($validated);

        return redirect()->route('owner.receipt.index')
            ->with('success', 'Pengaturan informasi nota dan toko berhasil diperbarui.');
    }
}
