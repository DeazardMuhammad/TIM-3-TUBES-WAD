<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kategori;

class KategoriController extends Controller
{
    // Constructor tidak diperlukan karena middleware sudah di route

    /**
     * Display a listing of the resource.
     * Menampilkan daftar kategori untuk admin
     */
    public function index()
    {
        $kategoris = Kategori::withCount(['lostItems', 'foundItems'])->paginate(10);
        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form tambah kategori baru
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
        ]);

        Kategori::create($request->all());

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail kategori
     */
    public function show(Kategori $kategori)
    {
        $kategori->loadCount(['lostItems', 'foundItems']);
        $lostItems = $kategori->lostItems()->with('user')->latest()->take(10)->get();
        $foundItems = $kategori->foundItems()->with('user')->latest()->take(10)->get();
        
        return view('admin.kategori.show', compact('kategori', 'lostItems', 'foundItems'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form edit kategori
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     * Update data kategori
     */
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama,' . $kategori->id,
        ]);

        $kategori->update($request->all());

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     * Hapus kategori
     */
    public function destroy(Kategori $kategori)
    {
        // Cek apakah kategori masih digunakan
        if ($kategori->lostItems()->count() > 0 || $kategori->foundItems()->count() > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh laporan.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}