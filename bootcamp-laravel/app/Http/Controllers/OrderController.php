<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan detail pesanan beserta riwayatnya.
     */
    public function show($id)
    {
        // Pastikan Model Order memiliki relasi 'histories' agar tidak error
        $order = Order::with(['histories.user'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Memperbarui status pesanan dan mencatat riwayatnya.
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        // 1. Update status pesanan di tabel orders
        $order->update(['status' => $request->status]);

        // 2. Simpan ke tabel order_histories untuk jejak audit
        OrderHistory::create([
            'order_id' => $order->id,
            'user_id'  => Auth::id(), // Mencatat admin/user yang melakukan update
            'status'   => $request->status,
            'notes'    => $request->notes ?? 'Status diubah dari ' . $oldStatus . ' ke ' . $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}