<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $title = "Temukan Gadget Impian Anda di GadgetShop";
        $description = "Pusat katalog terlengkap untuk menjelajahi gadget dan aksesoris teknologi berkualitas tinggi dengan jaminan garansi resmi 100%.";
        
        return view('pages.home', compact('title', 'description'));
    }

    public function about()
    {
        $title = "Tentang GadgetShop";
        $info = "GadgetShop adalah platform katalog modern yang mempermudah Anda dalam melakukan manajemen inventaris serta menjelajahi dunia teknologi digital secara dinamis.";
        
        return view('pages.about', compact('title', 'info'));
    }
}
