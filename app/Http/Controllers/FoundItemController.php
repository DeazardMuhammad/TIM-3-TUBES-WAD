<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\FoundItem;
use App\Models\Kategori;

class FoundItemController extends Controller
{
    /**
     * Menampilkan daftar barang ditemukan dengan fitur pencarian dan filter
     */
    public function index(Request $request)
    {
        $query = FoundItem::with(['user', 'kategori']);
        
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
        
        $foundItems = $query->latest()->paginate(12);
        $kategoriList = Kategori::all();

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $foundItems,
                'message' => 'Found items retrieved successfully'
            ]);
        }
        
        return view('found-items.index', compact('foundItems', 'kategoriList'));
    }


    public function create()
    {
        $kategoriList = Kategori::all();
        return view('found-items.create', compact('kategoriList'));
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
                $path = storage_path('app/public/images/found');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                
                // Simpan file
                $saved = $image->storeAs('images/found', $imageName, 'public');
                
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

        $foundItem = FoundItem::create($data);

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $foundItem->load(['user', 'kategori']),
                'message' => 'Found item created successfully'
            ], 201);
        }

        return redirect()->route('found-items.index')
            ->with('success', 'Laporan barang ditemukan berhasil dibuat.');
    }


    public function show(Request $request, FoundItem $foundItem)
    {
        // Semua user bisa melihat detail laporan (sesuai konsep Lost & Found)
        $foundItem->load(['user', 'kategori']);

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $foundItem,
                'message' => 'Found item retrieved successfully'
            ]);
        }

        return view('found-items.show', compact('foundItem'));
    }


    public function edit(FoundItem $foundItem)
    {
        // Hanya pemilik laporan atau admin yang bisa edit
        if (!Auth::user()->isAdmin() && $foundItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        $kategoriList = Kategori::all();
        return view('found-items.edit', compact('foundItem', 'kategoriList'));
    }


    public function update(Request $request, FoundItem $foundItem)
    {
        // Hanya pemilik laporan atau admin yang bisa update
        if (!Auth::user()->isAdmin() && $foundItem->user_id !== Auth::id()) {
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
                if ($foundItem->gambar) {
                    Storage::disk('public')->delete('images/found/' . $foundItem->gambar);
                }
                
                // Buat nama file yang aman
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Pastikan direktori ada
                $path = storage_path('app/public/images/found');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                
                // Simpan file
                $saved = $image->storeAs('images/found', $imageName, 'public');
                
                if ($saved) {
                    $data['gambar'] = $imageName;
                    \Log::info('Image updated successfully: ' . $imageName);
                }
            }
        }

        $foundItem->update($data);
        $foundItem->load(['user', 'kategori']);

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $foundItem,
                'message' => 'Found item updated successfully'
            ]);
        }

        return redirect()->route('found-items.index')
            ->with('success', 'Data barang ditemukan berhasil diupdate.');
    }


    public function destroy(Request $request, FoundItem $foundItem)
    {
        // Hanya pemilik laporan atau admin yang bisa hapus
        if (!Auth::user()->isAdmin() && $foundItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Hapus gambar jika ada
        if ($foundItem->gambar) {
            Storage::disk('public')->delete('images/found/' . $foundItem->gambar);
        }
        
        $foundItem->delete();

        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Found item deleted successfully'
            ]);
        }

        return redirect()->route('found-items.index')
            ->with('success', 'Laporan barang ditemukan berhasil dihapus.');
    }


    public function updateStatus(Request $request, FoundItem $foundItem)
    {
        // Hanya admin atau pemilik yang bisa update status
        if (!Auth::user()->isAdmin() && $foundItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        $request->validate([
            'status' => 'required|in:belum diambil,sudah diambil'
        ]);
        
        $foundItem->update(['status' => $request->status]);
        $foundItem->load(['user', 'kategori']);
        
        // Jika request dari API route atau ingin JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $foundItem,
                'message' => 'Found item status updated successfully'
            ]);
        }
        
        return back()->with('success', 'Status barang berhasil diupdate.');
    }
}
