<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    use AuthorizesRequests;

    private Guard $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        // auth middleware is applied in routes/web.php to protect the entire dashboard area
    }
    /**
     * Display the admin dashboard.
     *
     * @return View
     */
    public function index(): View
    {
        $applications = Application::query()
            ->with('documents')
            ->latest()
            ->paginate(20); // Adjust the number as needed

        // compile simple statistics using database queries
        $stats = [
            'total_applications' => Application::count(),
            'pending' => Application::whereIn('status', [Application::STATUS_PENDING, Application::STATUS_UNDER_REVIEW])->count(),
            'approved' => Application::where('status', Application::STATUS_APPROVED)->count(),
            'rejected' => Application::where('status', Application::STATUS_REJECTED)->count(),
        ];

        /** @var \App\Models\User|null $currentUser */
        $currentUser = $this->auth->user();

        return view('admin.dashboard', [
            'applications' => $applications,
            'stats' => $stats,
            'currentUser' => $currentUser,
        ]);
    }
}