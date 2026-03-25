<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreApplicationRequest;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(): View
    {
        return view('applications.create');
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $application = DB::transaction(function () use ($validated, $request) {
            $arrivalDate = new \DateTimeImmutable($validated['arrival_date']);
            $today = new \DateTimeImmutable('today');
            $overstayDays = max(0, (int) $arrivalDate->diff($today)->format('%a'));

            $application = Application::query()->create([
'user_id' => (int) $request->user()->id,
                'application_reference' => $this->makeReference(),
                'ack_ref_number' => $this->makeReference(),
                'submitted_at' => now(),
                'full_name' => $validated['full_name'],
                'passport_number' => $validated['passport_number'],
                'nationality' => $validated['nationality'],
                'visa_category' => $validated['visa_category'],
                'arrival_date' => $validated['arrival_date'],
                'overstay_days' => $overstayDays,
                'status' => Application::STATUS_PENDING,
                'applicant_note' => $validated['applicant_note'] ?? null,
            ]);

            $this->storeDocument($request, $application, 'passport_data_page');
            $this->storeDocument($request, $application, 'entry_visa');
            $this->storeDocument($request, $application, 'entry_stamp');
            $this->storeDocument($request, $application, 'return_ticket');

            return $application;
        });

        return redirect()
            ->route('dashboard')
            ->with('status', 'Application submitted successfully. Ref: '.$application->application_reference);
    }

private function makeReference(): string
    {
        return (string) rand(1000000000, 9999999999);
    }

    private function storeDocument(StoreApplicationRequest $request, Application $application, string $field): void
    {
        $file = $request->file($field);
        if (! $file) {
            return;
        }

        $storedName = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $storagePath = $file->storeAs('applications/'.$application->id, $storedName, 'local');

        ApplicationDocument::query()->create([
            'application_id' => $application->id,
            'document_type' => $field,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'storage_disk' => 'local',
            'storage_path' => $storagePath,
            'mime_type' => (string) $file->getClientMimeType(),
            'size_bytes' => (int) $file->getSize(),
        ]);
    }

    public function download(ApplicationDocument $document)
    {
        /** @var \App\Models\User|null $user */
        $user = request()->user();

        if (! $user instanceof \App\Models\User) {
            abort(403);
        }

        $isOwner = (int) $document->application->user_id === (int) $user->id;
        $isPrivileged = $user->hasAnyRole([
            \App\Models\User::ROLE_REVIEWER,
            \App\Models\User::ROLE_ADMIN,
            \App\Models\User::ROLE_SUPERADMIN,
        ]);

        if (! $isOwner && ! $isPrivileged) {
            abort(403);
        }

        if (! Storage::disk($document->storage_disk)->exists($document->storage_path)) {
            abort(404);
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($document->storage_disk);

        return $disk->download($document->storage_path, $document->original_name);
    }
}
