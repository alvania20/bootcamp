@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Pesanan #{{ $order->order_number }}</h2>
    
    <!-- Informasi Pesanan Lainnya -->
    <p>Status: {{ ucfirst($order->status) }}</p>
    
    <hr>

    <h3>Riwayat Pesanan</h3>
    <ul>
        @forelse($order->histories as $history)
            <li>
                <strong>{{ ucfirst($history->status) }}</strong> - 
                {{ $history->created_at->format('d M Y H:i') }}
                <br>
                <small>
                    Catatan: {{ $history->notes }} 
                    (Oleh: {{ $history->user->name ?? 'System' }})
                </small>
            </li>
        @empty
            <li>Belum ada riwayat perubahan status.</li>
        @endforelse
    </ul>
</div>
@endsection