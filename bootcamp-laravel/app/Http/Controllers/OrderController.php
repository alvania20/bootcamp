<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan detail pesanan beserta item yang dibeli, data pembeli, dan riwayatnya.
     */
    public function show($id)
    {
        // Menggunakan Eager Loading untuk efisiensi query
        // Memuat data user, item pesanan (beserta produknya), dan histori
        $order = Order::with(['user', 'orderItems.product', 'histories.user'])
                      ->findOrFail($id);
                      
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
            'notes'  => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        // Update status pesanan
        $order->update(['status' => $request->status]);

        // Simpan ke tabel order_histories untuk jejak audit
        OrderHistory::create([
            'order_id' => $order->id,
            'user_id'  => Auth::id(), 
            'status'   => $request->status,
            'notes'    => $request->notes ?? "Status diubah dari '{$oldStatus}' ke '{$request->status}'"
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}