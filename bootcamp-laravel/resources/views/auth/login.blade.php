@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center bg-slate-50 min-h-[80vh] px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-xl border border-slate-100">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-slate-900">
                Selamat Datang Kembali
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Silakan masuk untuk melanjutkan ke GadgetShop
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="text-red-700 text-sm font-medium">
                    Terjadi kesalahan saat login:
                    <ul class="list-disc ml-4 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-6">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition" 
                           placeholder="admin@gadgetshop.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Kata Sandi</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition" 
                           placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    Masuk ke Sistem
                </button>
            </div>
        </form>
    </div>
</div>
@endsection