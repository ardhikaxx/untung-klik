<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\CapitalEntry;
use Illuminate\Http\Request;

class CapitalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = CapitalEntry::where('business_id', $user->business_id)->with('user');

        if ($request->filled('start_date')) {
            $query->whereDate('entry_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('entry_date', '<=', $request->end_date);
        }

        $capitalEntries = $query->latest('entry_date')->latest('id')->paginate(15);
        $totalCapital = CapitalEntry::where('business_id', $user->business_id)->sum('amount');

        return view('owner.capital.index', compact('capitalEntries', 'totalCapital'));
    }

    public function create()
    {
        return view('owner.capital.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'entry_date' => 'required|date',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        CapitalEntry::create([
            'business_id' => $user->business_id,
            'user_id' => $user->id,
            'amount' => clean_number($request->amount),
            'entry_date' => $request->entry_date,
            'source' => $request->source,
            'description' => $request->description,
        ]);

        return redirect()->route('owner.capital.index')
            ->with('success', 'Modal berhasil dicatat.');
    }

    public function edit(CapitalEntry $capital)
    {
        if ($capital->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        return view('owner.capital.edit', compact('capital'));
    }

    public function update(Request $request, CapitalEntry $capital)
    {
        if ($capital->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'entry_date' => 'required|date',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $capital->update([
            'amount' => clean_number($request->amount),
            'entry_date' => $request->entry_date,
            'source' => $request->source,
            'description' => $request->description,
        ]);

        return redirect()->route('owner.capital.index')
            ->with('success', 'Modal berhasil diperbarui.');
    }

    public function destroy(CapitalEntry $capital)
    {
        if ($capital->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $capital->delete();

        return redirect()->route('owner.capital.index')
            ->with('success', 'Modal berhasil dihapus.');
    }
}
