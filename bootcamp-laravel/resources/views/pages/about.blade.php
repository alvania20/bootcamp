@extends('layouts.app')

@section('title', 'Tentang GadgetShop')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-md border border-slate-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full opacity-60"></div>
        
        <h2 class="text-3xl font-extrabold text-slate-900 mb-4">{{ $title }}</h2>
        <p class="text-lg text-slate-500 leading-relaxed mb-8">
            {{ $info }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-start gap-3">
                <span class="bg-indigo-100 text-indigo-700 p-2 rounded-xl mt-0.5">
                    <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </span>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Upload Gambar Nyata</h4>
                    <p class="text-xs text-slate-500 mt-1">File disimpan rapi di public/img/.</p>
                </div>
            </div>
            
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-start gap-3">
                <span class="bg-violet-100 text-violet-700 p-2 rounded-xl mt-0.5">
                    <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </span>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Respons Kencang</h4>
                    <p class="text-xs text-slate-500 mt-1">Menggunakan standard routing resource Laravel.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection