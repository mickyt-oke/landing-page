<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly Guard $auth)
    {
        // auth middleware is applied in routes/web.php to protect the entire dashboard area
    }

    public function index(): View
    {
        $applications = Application::query()
            ->with('documents')
            ->where('user_id', $this->auth->id())
            ->latest()
            ->get();

        // compile simple statistics
        $stats = [
            'total_applications' => $applications->count(),
            // treat both submitted and under_review as pending
            'pending' => $applications->whereIn('status', [Application::STATUS_PENDING, Application::STATUS_UNDER_REVIEW])->count(),
            'approved' => $applications->where('status', Application::STATUS_APPROVED)->count(),
            'rejected' => $applications->where('status', Application::STATUS_REJECTED)->count(),
        ];

        $currentUser = $this->auth->user();

        $latestApplication = $applications->first();
        
        return view('dashboard', [
            'applications' => $applications,
            'stats' => $stats,
            'currentUser' => $currentUser,
            'latestAckRef' => $latestApplication?->ack_ref_number,
            'latestApplication' => $latestApplication,
        ]);
    }


}
