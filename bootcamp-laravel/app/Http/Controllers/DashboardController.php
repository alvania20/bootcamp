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

        // 1. Ringkasan Statistik (Admin lihat semua, User lihat miliknya)
        $data = [
            'totalProduk'   => $isAdmin ? Product::count() : 0,
            'totalKategori' => $isAdmin ? Category::count() : 0,
            'totalOrder'    => $isAdmin ? Order::count() : $user->orders()->count(),
        ];

        // 2. Data Grafik Transaksi
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        $query = Order::query();
        
        // Filter: Jika bukan admin, hanya ambil order milik user tersebut
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $orders = $query->select(
            DB::raw("DATE(created_at) as date"),
            DB::raw('COUNT(id) as total_orders'),
            DB::raw('SUM(total_price) as total_revenue')
        )
        ->where('status', '!=', 'cancelled')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->get()
        ->keyBy('date');

        // (Logika loop grafik tetap sama...)
        $grafikData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $grafikData->push([
                'date' => $date,
                'total_orders' => $orders->has($date) ? $orders[$date]->total_orders : 0,
                'total_revenue' => $orders->has($date) ? (float) $orders[$date]->total_revenue : 0,
            ]);
        }

        // 3. Transaksi Terbaru
        $queryTransaksi = Order::query()->with('user');
        if (!$isAdmin) {
            $queryTransaksi->where('user_id', $user->id);
        }
        $transaksiTerbaru = $queryTransaksi->latest()->take(5)->get();

        return view('dashboard', [
            'data'             => $data,
            'labels'           => $grafikData->pluck('date'),
            'dataOrders'       => $grafikData->pluck('total_orders'),
            'dataRevenue'      => $grafikData->pluck('total_revenue'),
            'transaksiTerbaru' => $transaksiTerbaru,
            'isAdmin'          => $isAdmin // Kirim ini ke view agar bisa tampilkan komponen yang sesuai
        ]);
    }
}