@extends('layouts.app')

@section('title', 'Beranda - GadgetShop')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        
        <div class="space-y-6">
            <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                Sistem Manajemen Terintegrasi v2.0
            </div>
            
            <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 leading-tight">
                {{ $title }}
            </h1>
            
            <p class="text-lg text-slate-600 leading-relaxed max-w-lg">
                {{ $description }}
            </p>
            
            <div class="flex flex-wrap gap-4 pt-2">
                <a href="{{ route('products.index') }}" class="group bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-bold transition-all shadow-lg shadow-indigo-200 flex items-center gap-2">
                    Kelola Semua Produk 
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('page.about') }}" class="bg-white border-2 border-slate-200 hover:border-indigo-300 text-slate-700 hover:text-indigo-600 px-8 py-4 rounded-2xl font-bold transition-all">
                    Tentang Aplikasi
                </a>
            </div>
        </div>

        <div class="relative">
            <div class="absolute -inset-4 bg-gradient-to-tr from-indigo-100 to-violet-100 rounded-[2rem] blur-2xl opacity-50"></div>
            
            <div class="relative bg-white border border-slate-100 p-8 rounded-[2rem] shadow-2xl shadow-indigo-100/50">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Dashboard Ringkas</h3>
                    </div>
                    <span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-3 py-1 rounded-full border border-emerald-100 flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Aktif
                    </span>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-sm font-semibold text-slate-600">Sistem Database</span>
                        <span class="text-sm font-bold text-emerald-600 px-3 py-1 bg-white rounded-lg shadow-sm border border-slate-100">Terhubung</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection