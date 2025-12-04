<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Show notes for a specific report
     */
    public function show(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:lost,found',
            'report_id' => 'required|integer',
        ]);

        $reportType = $request->report_type;
        $reportId = $request->report_id;

        // Get the item
        if ($reportType === 'lost') {
            $item = LostItem::with('kategori')->findOrFail($reportId);
        } else {
            $item = FoundItem::with('kategori')->findOrFail($reportId);
        }

        // Get notes
        $notes = Note::with('admin')
            ->where('report_type', $reportType)
            ->where('report_id', $reportId)
            ->latest()
            ->get();

        return view('admin.notes.show', compact('item', 'notes', 'reportType'));
    }

    /**
     * Store new note (Admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:lost,found',
            'report_id' => 'required|integer',
            'isi_catatan' => 'required|string|max:2000',
        ]);

        // Verify report exists
        if ($request->report_type === 'lost') {
            LostItem::findOrFail($request->report_id);
        } else {
            FoundItem::findOrFail($request->report_id);
        }

        Note::create([
            'admin_id' => Auth::id(),
            'report_type' => $request->report_type,
            'report_id' => $request->report_id,
            'isi_catatan' => $request->isi_catatan,
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    /**
     * Delete note (Admin only)
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return redirect()->back()->with('success', 'Catatan berhasil dihapus.');
    }

    /**
     * Get notes for a report (AJAX)
     */
    public function getNotes(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:lost,found',
            'report_id' => 'required|integer',
        ]);

        $notes = Note::with('admin')
            ->where('report_type', $request->report_type)
            ->where('report_id', $request->report_id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'notes' => $notes,
        ]);
    }
}

