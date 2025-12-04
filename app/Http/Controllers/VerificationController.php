<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Display list of pending verifications (Admin only)
     */
    public function index()
    {
        $pendingLostItems = LostItem::with(['user', 'kategori'])
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->get();

        $pendingFoundItems = FoundItem::with(['user', 'kategori'])
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->get();

        return view('admin.verifikasi.index', compact('pendingLostItems', 'pendingFoundItems'));
    }

    /**
     * Approve lost item
     */
    public function approveLostItem($id)
    {
        $item = LostItem::findOrFail($id);

        $item->update([
            'status_verifikasi' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Send notification to user
        Notification::create([
            'user_id' => $item->user_id,
            'title' => 'Laporan Disetujui',
            'message' => 'Laporan barang hilang "' . $item->nama . '" telah disetujui oleh admin.',
            'link' => route('lost-items.show', $item->id),
            'type' => 'success',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil disetujui.');
    }

    /**
     * Reject lost item
     */
    public function rejectLostItem(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:1000',
        ]);

        $item = LostItem::findOrFail($id);

        $item->update([
            'status_verifikasi' => 'rejected',
            'alasan_reject' => $request->alasan_reject,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Send notification to user
        Notification::create([
            'user_id' => $item->user_id,
            'title' => 'Laporan Ditolak',
            'message' => 'Laporan barang hilang "' . $item->nama . '" ditolak. Alasan: ' . $request->alasan_reject,
            'link' => route('lost-items.show', $item->id),
            'type' => 'danger',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil ditolak.');
    }

    /**
     * Approve found item
     */
    public function approveFoundItem($id)
    {
        $item = FoundItem::findOrFail($id);

        $item->update([
            'status_verifikasi' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Send notification to user
        Notification::create([
            'user_id' => $item->user_id,
            'title' => 'Laporan Disetujui',
            'message' => 'Laporan barang ditemukan "' . $item->nama . '" telah disetujui oleh admin.',
            'link' => route('found-items.show', $item->id),
            'type' => 'success',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil disetujui.');
    }

    /**
     * Reject found item
     */
    public function rejectFoundItem(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:1000',
        ]);

        $item = FoundItem::findOrFail($id);

        $item->update([
            'status_verifikasi' => 'rejected',
            'alasan_reject' => $request->alasan_reject,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Send notification to user
        Notification::create([
            'user_id' => $item->user_id,
            'title' => 'Laporan Ditolak',
            'message' => 'Laporan barang ditemukan "' . $item->nama . '" ditolak. Alasan: ' . $request->alasan_reject,
            'link' => route('found-items.show', $item->id),
            'type' => 'danger',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil ditolak.');
    }
}

