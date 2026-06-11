<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Order::query()->with('user');

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $orders = $query->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product', 'histories.user'])
                      ->findOrFail($id);

        $this->authorizeAccess($order);

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Pastikan user terautentikasi dan memiliki akses admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
            'notes'  => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        
        // Transaction untuk menjaga konsistensi data antara Order dan History
        DB::transaction(function () use ($order, $validated, $oldStatus) {
            $order->update(['status' => $validated['status']]);

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id'  => Auth::id(), // Sekarang aman karena kolom sudah ada
                'status'   => $validated['status'],
                'notes'    => $validated['notes'] ?? "Status berubah: {$oldStatus} -> {$validated['status']}"
            ]);
        });

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    private function authorizeAccess(Order $order): void
    {
        $user = Auth::user();
        
        // Memastikan $user tidak null sebelum mengecek ID
        $isAdmin = $user?->isAdmin();
        $isOwner = $user && (int)$order->user_id === (int)$user->id;

        if (!$isAdmin && !$isOwner) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat pesanan ini.');
        }
    }
}