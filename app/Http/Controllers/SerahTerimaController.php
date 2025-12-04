<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\SerahTerima;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SerahTerimaController extends Controller
{
    /**
     * Display serah terima page
     */
    public function show($claimId)
    {
        $claim = Claim::with(['user', 'foundItem', 'foundItem.user', 'serahTerima'])
            ->findOrFail($claimId);

        // Only accepted claims can do serah terima
        if ($claim->status !== 'accepted') {
            return redirect()->back()->with('error', 'Klaim belum disetujui.');
        }

        // Create serah terima record if not exists
        if (!$claim->serahTerima) {
            SerahTerima::create([
                'claim_id' => $claim->id,
                'status' => 'pending',
            ]);
            $claim->load('serahTerima');
        }

        return view('serah-terima.show', compact('claim'));
    }

    /**
     * User uploads bukti serah terima
     */
    public function uploadUserBukti(Request $request, $claimId)
    {
        $request->validate([
            'bukti_serah_terima_user' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $claim = Claim::findOrFail($claimId);
        $serahTerima = $claim->serahTerima;

        if (!$serahTerima) {
            $serahTerima = SerahTerima::create(['claim_id' => $claim->id]);
        }

        // Handle image upload
        $imagePath = time() . '_user_' . $request->file('bukti_serah_terima_user')->getClientOriginalName();
        $request->file('bukti_serah_terima_user')->storeAs('public/images/serah-terima', $imagePath);

        $serahTerima->update([
            'bukti_serah_terima_user' => $imagePath,
            'user_uploaded_at' => now(),
            'status' => 'user_uploaded',
        ]);

        // Notify admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Bukti Serah Terima User',
                'message' => 'User telah mengupload bukti serah terima untuk barang "' . $claim->foundItem->nama . '"',
                'link' => route('admin.serah-terima.show', $claim->id),
                'type' => 'info',
            ]);
        }

        return redirect()->back()->with('success', 'Bukti serah terima berhasil diupload.');
    }

    /**
     * Admin uploads bukti serah terima
     */
    public function uploadAdminBukti(Request $request, $claimId)
    {
        $request->validate([
            'bukti_serah_terima_admin' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $claim = Claim::findOrFail($claimId);
        $serahTerima = $claim->serahTerima;

        if (!$serahTerima) {
            return redirect()->back()->with('error', 'Serah terima tidak ditemukan.');
        }

        // Handle image upload
        $imagePath = time() . '_admin_' . $request->file('bukti_serah_terima_admin')->getClientOriginalName();
        $request->file('bukti_serah_terima_admin')->storeAs('public/images/serah-terima', $imagePath);

        $serahTerima->update([
            'bukti_serah_terima_admin' => $imagePath,
            'admin_uploaded_at' => now(),
        ]);

        // Check if both uploaded - mark as completed
        if ($serahTerima->bukti_serah_terima_user && $serahTerima->bukti_serah_terima_admin) {
            $serahTerima->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Notify user
            Notification::create([
                'user_id' => $claim->user_id,
                'title' => 'Serah Terima Selesai',
                'message' => 'Proses serah terima barang "' . $claim->foundItem->nama . '" telah selesai. Silakan berikan rating.',
                'link' => route('feedback.create', $claim->id),
                'type' => 'success',
            ]);

            // Notify uploader
            Notification::create([
                'user_id' => $claim->foundItem->user_id,
                'title' => 'Serah Terima Selesai',
                'message' => 'Proses serah terima barang "' . $claim->foundItem->nama . '" telah selesai.',
                'link' => route('found-items.show', $claim->foundItem->id),
                'type' => 'success',
            ]);
        }

        return redirect()->back()->with('success', 'Bukti serah terima admin berhasil diupload.');
    }

    /**
     * Admin view serah terima
     */
    public function adminShow($claimId)
    {
        $claim = Claim::with(['user', 'foundItem', 'foundItem.user', 'serahTerima'])
            ->findOrFail($claimId);

        return view('admin.serah-terima.show', compact('claim'));
    }

    /**
     * List all serah terima (Admin)
     */
    public function adminIndex()
    {
        $serahTerimas = SerahTerima::with(['claim.user', 'claim.foundItem'])
            ->latest()
            ->paginate(15);

        return view('admin.serah-terima.index', compact('serahTerimas'));
    }
}

