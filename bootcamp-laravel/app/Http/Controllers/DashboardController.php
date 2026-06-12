<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        // 1. Ringkasan Statistik
        $data = [
            'totalProduk'   => $isAdmin ? Product::count() : 0,
            'totalKategori' => $isAdmin ? Category::count() : 0,
            'totalOrder'    => $isAdmin ? Order::count() : $user->orders()->count(),
        ];

        // 2. Data Grafik Transaksi (Inisialisasi wajib ada agar tidak error di JS)
        $labels = [];
        $dataOrders = [];
        $dataRevenue = [];

        if ($isAdmin) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(6)->startOfDay();

            $orders = Order::query()
                ->select(
                    DB::raw("DATE(created_at) as date"),
                    DB::raw('COUNT(id) as total_orders'),
                    DB::raw('SUM(total_price) as total_revenue')
                )
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->get()
                ->keyBy('date');

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $labels[] = $date;
                $dataOrders[] = $orders->has($date) ? $orders[$date]->total_orders : 0;
                $dataRevenue[] = $orders->has($date) ? (float) $orders[$date]->total_revenue : 0;
            }
        }

        // 3. Transaksi Terbaru
        $transaksiTerbaru = Order::query()
            ->with('user')
            ->when(!$isAdmin, function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Pastikan variabel dikirim ke view
        return view('dashboard', compact(
            'data', 
            'labels', 
            'dataOrders', 
            'dataRevenue', 
            'transaksiTerbaru', 
            'isAdmin'
        ));
    }
}