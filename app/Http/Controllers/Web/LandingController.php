<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        // load country list from public assets (could also be moved to config or database)
        $path = public_path('assets/data/countries.json');
        $countries = [];

        if (file_exists($path)) {
            $countries = json_decode(file_get_contents($path), true) ?: [];
        }

        return view('index', compact('countries'));
    }

//  Add login and registration handling if needed, or keep it as is since Laravel Breeze handles it separately
    public function login(): View
    {
        return view('auth.login');
    }
}
