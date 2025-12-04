<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Feedback;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Show feedback form
     */
    public function create($claimId)
    {
        $claim = Claim::with(['foundItem', 'serahTerima', 'feedback'])
            ->findOrFail($claimId);

        // Check if user is the claimant
        if ($claim->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Check if serah terima is completed
        if (!$claim->serahTerima || !$claim->serahTerima->isCompleted()) {
            return redirect()->back()->with('error', 'Serah terima belum selesai.');
        }

        // Check if feedback already exists
        if ($claim->feedback) {
            return redirect()->back()->with('info', 'Anda sudah memberikan feedback untuk klaim ini.');
        }

        return view('feedback.create', compact('claim'));
    }

    /**
     * Store feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'claim_id' => 'required|exists:claims,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $claim = Claim::findOrFail($request->claim_id);

        // Verify user is the claimant
        if ($claim->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Check if feedback already exists
        if ($claim->feedback) {
            return redirect()->back()->with('error', 'Anda sudah memberikan feedback.');
        }

        Feedback::create([
            'claim_id' => $request->claim_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        // Notify admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Feedback Baru',
                'message' => 'Feedback diterima untuk barang "' . $claim->foundItem->nama . '" (Rating: ' . $request->rating . '/5)',
                'link' => route('admin.feedback.index'),
                'type' => 'info',
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Terima kasih atas feedback Anda!');
    }

    /**
     * Admin view all feedback
     */
    public function index()
    {
        $feedbacks = Feedback::with(['user', 'claim.foundItem'])
            ->latest()
            ->paginate(20);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    /**
     * Admin view single feedback
     */
    public function show($id)
    {
        $feedback = Feedback::with(['user', 'claim.foundItem', 'claim.foundItem.kategori'])
            ->findOrFail($id);

        return view('admin.feedback.show', compact('feedback'));
    }
}

