<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Ringkasan Statistik
        $data = [
            'totalProduk'   => Product::count(),
            'totalKategori' => Category::count(),
            'totalKlik'     => (int) Product::sum('views'),
        ];

        // 2. Data Grafik Transaksi (Dual Axis - 7 Hari Terakhir)
        // Gunakan startOfDay() untuk memastikan range waktu konsisten
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        // Mengambil data transaksi dan mengelompokkan per tanggal
        $orders = Order::select(
            DB::raw("DATE(created_at) as date"),
            DB::raw('COUNT(id) as total_orders'),
            DB::raw('SUM(total_price) as total_revenue')
        )
        ->where('status', '!=', 'cancelled')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->get()
        ->keyBy('date');

        // Membangun array 7 hari untuk memastikan grafik tidak bolong
        $grafikData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $grafikData->push([
                'date'          => $date,
                'total_orders'  => $orders->has($date) ? $orders[$date]->total_orders : 0,
                'total_revenue' => $orders->has($date) ? (float) $orders[$date]->total_revenue : 0,
            ]);
        }

        // 3. Transaksi Terbaru (Lazy loading relasi user)
        $transaksiTerbaru = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'data'             => $data,
            'labels'           => $grafikData->pluck('date'),
            'dataOrders'       => $grafikData->pluck('total_orders'),
            'dataRevenue'      => $grafikData->pluck('total_revenue'),
            'transaksiTerbaru' => $transaksiTerbaru
        ]);
    }
}