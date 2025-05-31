<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\LostItem;
use App\Models\Kategori;

class LostItemController extends Controller
{

    public function index(Request $request)
    {
        $query = LostItem::with(['user', 'kategori']);
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        
        // Filter untuk melihat laporan sendiri saja (optional)
        if ($request->filled('my_reports') && $request->my_reports == 'true') {
            $query->where('user_id', Auth::id());
        }
        
        // Semua user bisa melihat semua laporan (sesuai konsep Lost & Found)
        
        $lostItems = $query->latest()->paginate(12);
        $kategoriList = Kategori::all();

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $lostItems,
                'message' => 'Lost items retrieved successfully'
            ]);
        }
        
        return view('lost-items.index', compact('lostItems', 'kategoriList'));
    }


    public function create()
    {
        $kategoriList = Kategori::all();
        return view('lost-items.create', compact('kategoriList'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kontak' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        
        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            
            // Validasi tambahan
            if ($image->isValid()) {
                // Buat nama file yang aman
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Pastikan direktori ada
                $path = storage_path('app/public/images/lost');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                
                // Simpan file
                $saved = $image->storeAs('images/lost', $imageName, 'public');
                
                if ($saved) {
                    $data['gambar'] = $imageName;
                    \Log::info('Image uploaded successfully: ' . $imageName);
                } else {
                    \Log::error('Failed to save image');
                }
            } else {
                \Log::error('Invalid image file: ' . $image->getErrorMessage());
            }
        }

        $lostItem = LostItem::create($data);

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $lostItem->load(['user', 'kategori']),
                'message' => 'Lost item created successfully'
            ], 201);
        }

        return redirect()->route('lost-items.index')
            ->with('success', 'Laporan barang hilang berhasil dibuat.');
    }


    public function show(Request $request, LostItem $lostItem)
    {
        // Semua user bisa melihat detail laporan (sesuai konsep Lost & Found)
        $lostItem->load(['user', 'kategori']);

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $lostItem,
                'message' => 'Lost item retrieved successfully'
            ]);
        }

        return view('lost-items.show', compact('lostItem'));
    }


    public function edit(LostItem $lostItem)
    {
        // Hanya pemilik laporan atau admin yang bisa edit
        if (!Auth::user()->isAdmin() && $lostItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        $kategoriList = Kategori::all();
        return view('lost-items.edit', compact('lostItem', 'kategoriList'));
    }


    public function update(Request $request, LostItem $lostItem)
    {
        // Hanya pemilik laporan atau admin yang bisa update
        if (!Auth::user()->isAdmin() && $lostItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kontak' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'sometimes|in:belum diambil,sudah diambil'
        ]);

        $data = $request->all();
        
        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            
            if ($image->isValid()) {
                // Hapus gambar lama jika ada
                if ($lostItem->gambar) {
                    Storage::disk('public')->delete('images/lost/' . $lostItem->gambar);
                }
                
                // Buat nama file yang aman
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Pastikan direktori ada
                $path = storage_path('app/public/images/lost');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                
                // Simpan file
                $saved = $image->storeAs('images/lost', $imageName, 'public');
                
                if ($saved) {
                    $data['gambar'] = $imageName;
                    \Log::info('Image updated successfully: ' . $imageName);
                }
            }
        }

        $lostItem->update($data);
        $lostItem->load(['user', 'kategori']);

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $lostItem,
                'message' => 'Lost item updated successfully'
            ]);
        }

        return redirect()->route('lost-items.index')
            ->with('success', 'Data barang hilang berhasil diupdate.');
    }


    public function destroy(Request $request, LostItem $lostItem)
    {
        // Hanya pemilik laporan atau admin yang bisa hapus
        if (!Auth::user()->isAdmin() && $lostItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Hapus gambar jika ada
        if ($lostItem->gambar) {
            Storage::disk('public')->delete('images/lost/' . $lostItem->gambar);
        }
        
        $lostItem->delete();

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lost item deleted successfully'
            ]);
        }

        return redirect()->route('lost-items.index')
            ->with('success', 'Laporan barang hilang berhasil dihapus.');
    }


    public function updateStatus(Request $request, LostItem $lostItem)
    {
        // Hanya admin atau pemilik yang bisa update status
        if (!Auth::user()->isAdmin() && $lostItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        $request->validate([
            'status' => 'required|in:belum diambil,sudah diambil'
        ]);
        
        $lostItem->update(['status' => $request->status]);
        $lostItem->load(['user', 'kategori']);
        
        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $lostItem,
                'message' => 'Lost item status updated successfully'
            ]);
        }
        
        return back()->with('success', 'Status barang berhasil diupdate.');
    }
}
