<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Sponsor;
use App\Models\Temoignage;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeProducts = Product::query()
            ->where('is_active', true)
            ->with(['category', 'media'])
            ->latest();

        $featuredProducts = (clone $activeProducts)
            ->where('is_featured', true)
            ->take(10)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = (clone $activeProducts)
                ->take(10)
                ->get();
        }

        $clothingProducts = (clone $activeProducts)
            ->where('type', 'clothing')
            ->take(10)
            ->get();

        $settings = Setting::first();
        $sponsors = Sponsor::get();
        $temoignages = Temoignage::get();

        return view('index', compact('featuredProducts', 'clothingProducts', 'settings', 'sponsors', 'temoignages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function contact()
    {
        return view('contact');
    }
}
