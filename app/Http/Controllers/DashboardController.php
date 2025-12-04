<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Claim;
use App\Models\Feedback;
use App\Models\SerahTerima;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk mahasiswa
     */
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

    /**
     * Dashboard untuk admin
     */
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

    /**
     * Dashboard statistics untuk admin (with charts)
     */
    public function statistics()
    {
        // Total counts
        $totalUsers = User::where('role', 'mahasiswa')->count();
        $totalLostItems = LostItem::count();
        $totalFoundItems = FoundItem::count();
        $totalClaims = Claim::count();

        // Verification status
        $pendingVerification = LostItem::where('status_verifikasi', 'pending')->count() 
            + FoundItem::where('status_verifikasi', 'pending')->count();
        $approvedItems = LostItem::where('status_verifikasi', 'approved')->count() 
            + FoundItem::where('status_verifikasi', 'approved')->count();
        $rejectedItems = LostItem::where('status_verifikasi', 'rejected')->count() 
            + FoundItem::where('status_verifikasi', 'rejected')->count();

        // Claim status
        $pendingClaims = Claim::where('status', 'pending')->count();
        $acceptedClaims = Claim::where('status', 'accepted')->count();
        $rejectedClaims = Claim::where('status', 'rejected')->count();

        // Items by category
        $itemsByCategoryRaw = Kategori::withCount(['lostItems', 'foundItems'])->get();
        $itemsByCategory = $itemsByCategoryRaw->map(function ($kategori) {
            return [
                'name' => $kategori->nama,
                'lost' => $kategori->lost_items_count,
                'found' => $kategori->found_items_count,
                'total' => $kategori->lost_items_count + $kategori->found_items_count,
            ];
        })->values()->toArray();

        // Lost items found over time (last 6 months)
        $monthlyData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            
            $lostCount = LostItem::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $foundCount = FoundItem::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthlyData->push([
                'month' => $monthName,
                'lost' => $lostCount,
                'found' => $foundCount,
            ]);
        }
        $monthlyData = $monthlyData->toArray();

        // Average completion time (from claim accepted to serah terima completed)
        $avgCompletionTime = SerahTerima::whereNotNull('completed_at')
            ->join('claims', 'serah_terima.claim_id', '=', 'claims.id')
            ->whereNotNull('claims.reviewed_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, claims.reviewed_at, serah_terima.completed_at)) as avg_hours'))
            ->value('avg_hours');

        $avgCompletionTime = $avgCompletionTime ? round($avgCompletionTime, 1) : 0;

        // Average rating per category
        $ratingsByCategoryRaw = Kategori::select('kategori.nama')
            ->leftJoin('found_items', 'kategori.id', '=', 'found_items.kategori_id')
            ->leftJoin('claims', 'found_items.id', '=', 'claims.found_item_id')
            ->leftJoin('feedback', 'claims.id', '=', 'feedback.claim_id')
            ->groupBy('kategori.id', 'kategori.nama')
            ->selectRaw('AVG(feedback.rating) as avg_rating, COUNT(feedback.id) as feedback_count')
            ->get();
        
        $ratingsByCategory = $ratingsByCategoryRaw->map(function ($item) {
            return [
                'category' => $item->nama,
                'rating' => $item->avg_rating ? round($item->avg_rating, 1) : 0,
                'count' => $item->feedback_count,
            ];
        })->values()->toArray();

        // Recent activity
        $recentActivity = $this->getRecentActivity();

        return view('dashboard.statistics', compact(
            'totalUsers',
            'totalLostItems',
            'totalFoundItems',
            'totalClaims',
            'pendingVerification',
            'approvedItems',
            'rejectedItems',
            'pendingClaims',
            'acceptedClaims',
            'rejectedClaims',
            'itemsByCategory',
            'monthlyData',
            'avgCompletionTime',
            'ratingsByCategory',
            'recentActivity'
        ));
    }

    /**
     * Get recent activity for admin
     */
    private function getRecentActivity()
    {
        $activity = [];

        // Recent verifications
        $recentVerifications = LostItem::select('id', 'nama', 'status_verifikasi', 'verified_at', DB::raw("'lost' as type"))
            ->whereNotNull('verified_at')
            ->union(
                FoundItem::select('id', 'nama', 'status_verifikasi', 'verified_at', DB::raw("'found' as type"))
                    ->whereNotNull('verified_at')
            )
            ->orderBy('verified_at', 'desc')
            ->limit(10)
            ->get();

        return $recentVerifications;
    }

    /**
     * Get chart data (AJAX)
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'monthly');

        if ($type === 'monthly') {
            $data = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthName = $date->format('M');
                
                $lostCount = LostItem::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                    
                $foundCount = FoundItem::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $data[] = [
                    'month' => $monthName,
                    'lost' => $lostCount,
                    'found' => $foundCount,
                ];
            }

            return response()->json(['success' => true, 'data' => $data]);
        }

        return response()->json(['success' => false]);
    }
}
