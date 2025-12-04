<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    /**
     * Display claim form for a found item
     */
    public function create($foundItemId)
    {
        $foundItem = FoundItem::with(['user', 'kategori'])->findOrFail($foundItemId);

        // Check if user already has a pending/accepted claim
        $existingClaim = Claim::where('user_id', Auth::id())
            ->where('found_item_id', $foundItemId)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existingClaim) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan klaim untuk barang ini.');
        }

        // Check if user is the uploader
        if ($foundItem->user_id == Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa mengklaim barang yang Anda sendiri laporkan.');
        }

        return view('claims.create', compact('foundItem'));
    }

    /**
     * Store new claim
     */
    public function store(Request $request)
    {
        $request->validate([
            'found_item_id' => 'required|exists:found_items,id',
            'deskripsi_klaim' => 'required|string|max:2000',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $foundItem = FoundItem::findOrFail($request->found_item_id);

        // Handle bukti upload
        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = time() . '_' . $request->file('bukti')->getClientOriginalName();
            $request->file('bukti')->storeAs('public/images/claims', $buktiPath);
        }

        $claim = Claim::create([
            'user_id' => Auth::id(),
            'found_item_id' => $request->found_item_id,
            'deskripsi_klaim' => $request->deskripsi_klaim,
            'bukti' => $buktiPath,
            'status' => 'pending',
        ]);

        // Notify the uploader of the found item
        Notification::create([
            'user_id' => $foundItem->user_id,
            'title' => 'Ada Klaim Baru',
            'message' => 'Seseorang mengklaim barang "' . $foundItem->nama . '" yang Anda laporkan.',
            'link' => route('admin.claims.show', $claim->id),
            'type' => 'info',
        ]);

        return redirect()->route('found-items.show', $foundItem->id)
            ->with('success', 'Klaim berhasil diajukan. Menunggu verifikasi admin.');
    }

    /**
     * Display claims list (Admin)
     */
    public function index()
    {
        $claims = Claim::with(['user', 'foundItem', 'foundItem.kategori'])
            ->latest()
            ->paginate(15);

        return view('admin.claims.index', compact('claims'));
    }

    /**
     * Display single claim detail (Admin)
     */
    public function show($id)
    {
        $claim = Claim::with(['user', 'foundItem', 'foundItem.user', 'foundItem.kategori'])
            ->findOrFail($id);

        return view('admin.claims.show', compact('claim'));
    }

    /**
     * Accept claim (Admin)
     */
    public function accept($id)
    {
        $claim = Claim::findOrFail($id);

        $claim->update([
            'status' => 'accepted',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Update found item status
        $claim->foundItem->update(['status' => 'sudah diambil']);

        // Notify claimant
        Notification::create([
            'user_id' => $claim->user_id,
            'title' => 'Klaim Disetujui',
            'message' => 'Klaim Anda untuk barang "' . $claim->foundItem->nama . '" telah disetujui.',
            'link' => route('serah-terima.show', $claim->id),
            'type' => 'success',
        ]);

        // Notify uploader
        Notification::create([
            'user_id' => $claim->foundItem->user_id,
            'title' => 'Klaim Disetujui',
            'message' => 'Klaim untuk barang "' . $claim->foundItem->nama . '" telah disetujui oleh admin.',
            'link' => route('found-items.show', $claim->foundItem->id),
            'type' => 'info',
        ]);

        return redirect()->back()->with('success', 'Klaim berhasil disetujui.');
    }

    /**
     * Reject claim (Admin)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:1000',
        ]);

        $claim = Claim::findOrFail($id);

        $claim->update([
            'status' => 'rejected',
            'alasan_reject' => $request->alasan_reject,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Notify claimant
        Notification::create([
            'user_id' => $claim->user_id,
            'title' => 'Klaim Ditolak',
            'message' => 'Klaim Anda untuk barang "' . $claim->foundItem->nama . '" ditolak. Alasan: ' . $request->alasan_reject,
            'link' => route('found-items.show', $claim->foundItem->id),
            'type' => 'danger',
        ]);

        return redirect()->back()->with('success', 'Klaim berhasil ditolak.');
    }

    /**
     * User's claims list
     */
    public function myClaims()
    {
        $claims = Claim::with(['foundItem', 'foundItem.kategori'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('claims.my-claims', compact('claims'));
    }
}

