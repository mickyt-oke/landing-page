<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return redirect()
                ->route('home')
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
