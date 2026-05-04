<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;

class PageController extends Controller
{
    public function about()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $stats = [
            'customers' => Customer::count(),
            'products' => Product::where('is_active', true)->count(),
            'days' => Carbon::parse($settings['business_start_date'] ?? '2024-01-01')->diffInDays(now()),
        ];

        return view('customer.pages.about', compact('settings', 'stats'));
    }
    
    public function contact()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('customer.pages.contact', compact('settings'));
    }
    
    public function faq()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('customer.pages.faq', compact('settings'));
    }
}
