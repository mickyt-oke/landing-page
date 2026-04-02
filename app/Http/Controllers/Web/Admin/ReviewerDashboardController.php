<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\View\View;

class ReviewerDashboardController extends Controller
{
    public function __construct(private readonly Guard $auth) {}

    public function index(): View
    {
        $user = $this->auth->user();

        // Active queue: pending (to be picked up) + under_review (in progress)
        $activeApplications = Application::query()
            ->with('documents')
            ->whereIn('status', [Application::STATUS_PENDING, Application::STATUS_UNDER_REVIEW])
            ->latest()
            ->get();

        // Recently completed by any reviewer (last 20) — for context
        $recentCompleted = Application::query()
            ->with('reviewer:id,name')
            ->whereIn('status', [Application::STATUS_APPROVED, Application::STATUS_REJECTED])
            ->latest('reviewed_at')
            ->limit(20)
            ->get();

        $pendingCount     = $activeApplications->where('status', Application::STATUS_PENDING)->count();
        $underReviewCount = $activeApplications->where('status', Application::STATUS_UNDER_REVIEW)->count();

        // How many this reviewer has vetted (has reviewer_comment + reviewed_by = me, still under_review)
        $myVettedCount = Application::query()
            ->where('status', Application::STATUS_UNDER_REVIEW)
            ->where('reviewed_by', (int) $user?->id)
            ->whereNotNull('reviewer_comment')
            ->count();

        $stats = [
            // Required by admin header partial for nav badge
            'total_applications' => Application::count(),
            'pending'            => $pendingCount,
            'under_review'       => $underReviewCount,
            'my_vetted'          => $myVettedCount,
            'approved'           => Application::where('status', Application::STATUS_APPROVED)->count(),
            'rejected'           => Application::where('status', Application::STATUS_REJECTED)->count(),
        ];

        return view('admin.reviewer', [
            'activeApplications' => $activeApplications,
            'recentCompleted'    => $recentCompleted,
            'stats'              => $stats,
            'currentUser'        => $user,
        ]);
    }
}
