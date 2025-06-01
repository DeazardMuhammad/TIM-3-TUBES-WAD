<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends ControllerG
{
   
    public function index()
    {
        $user = Auth::user();
        
        // Statistik data untuk mahasiswa
        $totalLostItems = LostItem::count();
        $totalFoundItems = FoundItem::count();
        $myLostItems = LostItem::where('user_id', $user->id)->count();
        $myFoundItems = FoundItem::where('user_id', $user->id)->count();
        
        // Laporan terbaru global
        $recentLostItems = LostItem::with(['user', 'kategori'])
            ->latest()
            ->take(5)
            ->get();
            
        $recentFoundItems = FoundItem::with(['user', 'kategori'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.student', compact(
            'totalLostItems',
            'totalFoundItems', 
            'myLostItems',
            'myFoundItems',
            'recentLostItems',
            'recentFoundItems'
        ));
    }

   
    public function admin()
    {

        // Statistik global untuk admin
        $totalUsers = User::where('role', 'mahasiswa')->count();
        $totalLostItems = LostItem::count();
        $totalFoundItems = FoundItem::count();
        $totalKategori = Kategori::count();
        
        $lostItemsBelumDiambil = LostItem::belumDiambil()->count();
        $foundItemsBelumDiambil = FoundItem::belumDiambil()->count();
        
        // Data terbaru untuk admin
        $recentLostItems = LostItem::with(['user', 'kategori'])
            ->latest()
            ->take(10)
            ->get();
            
        $recentFoundItems = FoundItem::with(['user', 'kategori'])
            ->latest()
            ->take(10)
            ->get();
            
        $recentUsers = User::where('role', 'mahasiswa')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalLostItems',
            'totalFoundItems',
            'totalKategori',
            'lostItemsBelumDiambil',
            'foundItemsBelumDiambil',
            'recentLostItems',
            'recentFoundItems',
            'recentUsers'
        ));
    }
}


