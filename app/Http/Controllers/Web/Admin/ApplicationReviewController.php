<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ApproveApplicationRequest;
use App\Http\Requests\Web\RejectApplicationRequest;
use App\Http\Requests\Web\StartReviewRequest;
use App\Http\Requests\Web\VetApplicationRequest;
use App\Mail\ApplicationApproved;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApplicationReviewController extends Controller
{
    /**
     * Reviewer picks up a pending application → under_review.
     * Accessible by: reviewer, admin, superadmin.
     */
    public function startReview(StartReviewRequest $request, Application $application): RedirectResponse
    {
        $application->update([
            'status'      => Application::STATUS_UNDER_REVIEW,
            'reviewed_by' => (int) $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Application moved to Under Review.');
    }

    /**
     * Reviewer submits vetting notes (recommendation) for an under_review application.
     * Status stays under_review; admin will make the final approve/reject decision.
     * Accessible by: reviewer, admin, superadmin.
     */
    public function vet(VetApplicationRequest $request, Application $application): RedirectResponse
    {
        $data = $request->validated();

        $application->update([
            'reviewer_comment' => $data['reviewer_comment'],
            'reviewed_by'      => (int) $request->user()->id,
            'reviewed_at'      => now(),
        ]);

        return back()->with('status', 'Vetting notes saved. Application is ready for admin decision.');
    }

    /**
     * Admin approves an under_review application.
     * Accessible by: admin, superadmin.
     */
    public function approve(ApproveApplicationRequest $request, Application $application): RedirectResponse
    {
        $data = $request->validated();

        $application->update([
            'status'           => Application::STATUS_APPROVED,
            'reviewer_comment' => $data['reviewer_comment'] ?? $application->reviewer_comment,
            'rejection_reason' => null,
            'reviewed_at'      => now(),
            'reviewed_by'      => (int) $request->user()->id,
        ]);

        $application->loadMissing('user');
        Mail::to($application->user->email)
            ->send(new ApplicationApproved($application));

        return back()->with('status', 'Application approved successfully.');
    }

    /**
     * Reviewer or admin rejects an under_review application.
     * Accessible by: reviewer, admin, superadmin.
     */
    public function reject(RejectApplicationRequest $request, Application $application): RedirectResponse
    {
        $data = $request->validated();

        $application->update([
            'status'           => Application::STATUS_REJECTED,
            'rejection_reason' => $data['rejection_reason'],
            'reviewer_comment' => $data['reviewer_comment'],
            'reviewed_at'      => now(),
            'reviewed_by'      => (int) $request->user()->id,
        ]);

        return back()->with('status', 'Application rejected.');
    }

    /**
     * Download an application document (reviewer, admin, superadmin).
     */
    public function downloadDocument(ApplicationDocument $document): mixed
    {
        if (! Storage::disk($document->storage_disk)->exists($document->storage_path)) {
            abort(404);
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($document->storage_disk);

        return $disk->download($document->storage_path, $document->original_name);
    }
}
