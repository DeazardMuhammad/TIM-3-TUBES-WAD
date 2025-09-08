<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        
        $users = User::query()
            ->when($search, function($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                           ->orWhere('nim', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($role, function($query, $role) {
                return $query->where('role', $role);
            })
            ->withCount(['lostItems', 'foundItems'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'kontak' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:mahasiswa,admin',
        ]);

        User::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'email' => $request->email,
            'kontak' => $request->kontak,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Load counts untuk statistik
        $userLostItems = $user->lostItems()->count();
        $userFoundItems = $user->foundItems()->count();
        
        // Load recent items untuk ditampilkan
        $recentLostItems = $user->lostItems()->with('kategori')->latest()->take(5)->get();
        $recentFoundItems = $user->foundItems()->with('kategori')->latest()->take(5)->get();
        
        return view('admin.users.show', compact('user', 'userLostItems', 'userFoundItems', 'recentLostItems', 'recentFoundItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:users,nim,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'kontak' => 'required|string|max:100',
            'role' => 'required|in:mahasiswa,admin',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only(['nama', 'nim', 'email', 'kontak', 'role']);
        
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Cek apakah user masih memiliki laporan
        if ($user->lostItems()->count() > 0 || $user->foundItems()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak dapat dihapus karena masih memiliki laporan.');
        }

        // Tidak bisa hapus admin sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
