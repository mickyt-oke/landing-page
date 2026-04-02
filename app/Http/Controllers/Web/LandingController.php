<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        // load country list from public assets (could also be moved to config or database)
        $path = public_path('assets/data/countries.json');
        $countries = [];

        if (file_exists($path)) {
            $countries = json_decode(file_get_contents($path), true) ?: [];
        }

        return view('index', compact('countries'));
    }

    public function faq(): View
    {
        $faqItems = [
            [
                'question' => 'Who is required to use this portal?',
                'answer' => 'This portal is for foreign nationals affected by the Middle-East crisis who need to register their stay and remain compliant with Nigeria Immigration Service requirements.',
            ],
            [
                'question' => 'How do I apply on the portal?',
                'answer' => 'Click the Apply Now button on the landing page, sign in to your account, complete the required information, and submit the requested supporting documents for review.',
            ],
            [
                'question' => 'Is this portal free to use?',
                'answer' => 'Registration on the portal is provided as part of the support and documentation process. Follow any official guidance shown during your application if additional requirements are introduced.',
            ],
            [
                'question' => 'What documents may be required during registration?',
                'answer' => 'Applicants may be asked to provide valid travel identification, passport biodata information, evidence of entry or stay, and any other documents requested by the Nigeria Immigration Service.',
            ],
            [
                'question' => 'Can I track the status of my application?',
                'answer' => 'Yes. After submitting your application, use the Check Status option on the landing page or log in to your dashboard to monitor progress and review updates.',
            ],
            [
                'question' => 'What happens after I submit my application?',
                'answer' => 'Your submission will be reviewed by the appropriate officers. You may receive status updates, requests for clarification, or confirmation once the review process is completed.',
            ],
            [
                'question' => 'What should I do if I need help?',
                'answer' => 'If you need support, use the official contact channels provided by the Nigeria Immigration Service, including the support email and phone details shown on the website footer.',
            ],
        ];

        $path = public_path('assets/data/countries.json');
        $countries = [];

        if (file_exists($path)) {
            $countries = json_decode(file_get_contents($path), true) ?: [];
        }

        return view('faq', compact('faqItems' , 'countries'));
    }
/** 
 * *Login function with redirect to dashboard on success, or back to landing page with error on failure
 * *Redirect authenticated users to /dashboard
 * *Redirect authenticated admin users to /admin/dashboard
 * * Registration function to create new user accounts, with validation and redirect back to landing page with success message
 * * Logout function to end user session and redirect back to landing page
**/    
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isReviewer()) {
                return redirect()->route('admin.reviewer.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match system records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        \App\Models\User::create([
            'name' => trim($validated['sname'].' '.$validated['fname']),
            'surname' => $validated['sname'],
            'first_name' => $validated['fname'],
            'other_names' => null,
            'passport_number' => $validated['pptno'],
            'passport_type' => $validated['ppttype'],
            'nationality' => $validated['nationality'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => \App\Models\User::ROLE_USER,
        ]);

        return redirect('/')
            ->with('success', 'Registration successful. Please log in to continue.');
    }
}
