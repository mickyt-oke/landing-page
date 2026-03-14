<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $applications = Application::query()
            ->with('documents')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // compile simple statistics
        $stats = [
            'total_applications' => $applications->count(),
            // treat both submitted and under_review as pending
            'pending' => $applications->whereIn('status', [Application::STATUS_SUBMITTED, Application::STATUS_UNDER_REVIEW])->count(),
            'approved' => $applications->where('status', Application::STATUS_APPROVED)->count(),
            'rejected' => $applications->where('status', Application::STATUS_REJECTED)->count(),
        ];

        $currentUser = auth()->user();

        return view('dashboard', [
            'applications' => $applications,
            'stats' => $stats,
            'currentUser' => $currentUser,
        ]);
    }

    public function show(Application $application): View
    {
        $this->authorize('view', $application);

        return view('application.show', [
            'application' => $application->load('documents'),
        ]);
    }


}
