<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function berita(){

        $berita = Berita::latest()->first(); // Ambil hanya 1 berita terbaru
        $berita2 = Berita::latest()->skip(1)->first();
        $berita3 = Berita::latest()->skip(2)->first();

        return view('welcome', compact('berita', 'berita2' , 'berita3'));
    }
}
