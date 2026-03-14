<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ApproveApplicationRequest;
use App\Http\Requests\Web\RejectApplicationRequest;
use App\Http\Requests\Web\StartReviewRequest;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationReviewController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $query = Application::query()
            ->with(['user:id,name,email,role', 'reviewer:id,name,email,role'])
            ->latest();

        if ($status !== '') {
            $query->where('status', $status);
        }

        return view('admin.dashboard', [
            'applications' => $query->paginate(20)->withQueryString(),
            'selectedStatus' => $status,
        ]);
    }

    public function startReview(StartReviewRequest $request, Application $application): RedirectResponse
    {
        $application->update([
            'status' => Application::STATUS_UNDER_REVIEW,
        ]);

        return back()->with('status', 'Application moved to under review.');
    }

    public function approve(ApproveApplicationRequest $request, Application $application): RedirectResponse
    {
        $data = $request->validated();

        $application->update([
            'status' => Application::STATUS_APPROVED,
            'reviewer_comment' => $data['reviewer_comment'] ?? null,
            'rejection_reason' => null,
            'reviewed_at' => now(),
            'reviewed_by' => (int) $request->user()->id,
        ]);

        return back()->with('status', 'Application approved.');
    }

    public function reject(RejectApplicationRequest $request, Application $application): RedirectResponse
    {
        $data = $request->validated();

        $application->update([
            'status' => Application::STATUS_REJECTED,
            'rejection_reason' => $data['rejection_reason'],
            'reviewer_comment' => $data['reviewer_comment'],
            'reviewed_at' => now(),
            'reviewed_by' => (int) $request->user()->id,
        ]);

        return back()->with('status', 'Application rejected.');
    }
}
