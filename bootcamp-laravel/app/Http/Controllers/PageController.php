<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman utama (Home)
     */
    public function home()
    {
        $data = [
            'title' => "Temukan Gadget Impian Anda di GadgetShop",
            'description' => "Pusat katalog terlengkap untuk menjelajahi gadget dan aksesoris teknologi berkualitas tinggi dengan jaminan garansi resmi 100%."
        ];
        
        return view('pages.home', $data);
    }

    /**
     * Menampilkan halaman tentang kami (About)
     */
    public function about()
    {
        $data = [
            'title' => "Tentang GadgetShop",
            'info' => "GadgetShop adalah platform katalog modern yang mempermudah Anda dalam melakukan manajemen inventaris serta menjelajahi dunia teknologi digital secara dinamis."
        ];
        
        return view('pages.about', $data);
    }
}