@extends('layouts.app')

@section('title', 'Beranda - GadgetShop')

@section('content')
<div class="relative overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center py-6">
        <div class="lg:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                Sistem Manajemen Terintegrasi v2.0
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-none">
                {{ $title }}
            </h1>
            <p class="text-lg text-slate-500 leading-relaxed max-w-2xl">
                {{ $description }}
            </p>
            <div class="pt-4 flex flex-wrap gap-4">
                <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white px-6 py-3.5 rounded-xl transition duration-200 shadow-xl shadow-indigo-100 font-semibold flex items-center gap-2">
                    Kelola Semua Produk 
                    <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                <a href="{{ route('page.about') }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-6 py-3.5 rounded-xl font-semibold transition duration-200">
                    Tentang Aplikasi
                </a>
            </div>
        </div>

        <div class="lg:col-span-5 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-violet-400 rounded-3xl blur-3xl opacity-20 transform -rotate-6"></div>
            <div class="relative bg-white border border-slate-200 p-8 rounded-3xl shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <span class="text-xs font-bold text-slate-400 uppercase">Dashboard Ringkas</span>
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <span class="text-sm font-semibold text-slate-600">Sistem Database</span>
                        <span class="text-sm font-bold text-emerald-600">Terhubung (Connected)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection