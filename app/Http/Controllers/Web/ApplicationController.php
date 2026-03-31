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
        /** @var \App\Models\User $user */
        $user = request()->user();

        $nationalitiesPath = public_path('assets/data/countries.json');
        $nationalities = [];

        if (file_exists($nationalitiesPath)) {
            $decoded = json_decode(file_get_contents($nationalitiesPath), true) ?: [];
            $nationalities = array_column($decoded, 'name');
        }
        

        $prefill = [
            'regSurname' => old('surname', $user->surname),
            'regFirstName' => old('first_name', $user->first_name),
            'regOtherNames' => old('other_names', $user->other_names),
            'regPassport' => old('passport_number', $user->passport_number),
            'regNationality' => old('nationality', $user->nationality),
        ];

        return view('applications.create', compact('nationalities', 'prefill'));
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /** @var \App\Models\Application $application */
        $application = DB::transaction(function () use ($validated, $request) {
            $arrivalDate = new \DateTimeImmutable($validated['arrival_date']);
            $today = new \DateTimeImmutable('today');
            $overstayDays = max(0, (int) $arrivalDate->diff($today)->format('%a'));

            $application = Application::query()->create([
                'user_id' => (int) $request->user()->id,
                'application_reference' => $this->makeReference(),
                'ack_ref_number' => $this->makeReference(),
                'submitted_at' => now(),
                'full_name' => $validated['surname'].' '.$validated['first_name'].' '.$validated['other_names'],
                'passport_number' => $validated['passport_number'],
                'nationality' => $validated['nationality'],
                'visa_category' => $validated['visa_category'],
                'arrival_date' => $validated['arrival_date'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
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
            ->route('applications.success', $application)
            ->with('status', 'Application submitted successfully.');
    }

    public function show(Application $application): View
    {
        $this->authorizeApplicationAccess($application);

        return view('applications.success', [
            'application' => $application->loadMissing('documents'),
        ]);
    }

    public function acknowledgement(Application $application): View
    {
        $this->authorizeApplicationAccess($application);

        return view('applications.acknowledgement', [
            'application' => $application->loadMissing('documents'),
        ]);
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

    private function authorizeApplicationAccess(Application $application): void
    {
        /** @var \App\Models\User|null $user */
        $user = request()->user();

        if (! $user instanceof \App\Models\User) {
            abort(403);
        }

        $isOwner = (int) $application->user_id === (int) $user->id;
        $isPrivileged = $user->hasAnyRole([
            \App\Models\User::ROLE_REVIEWER,
            \App\Models\User::ROLE_ADMIN,
            \App\Models\User::ROLE_SUPERADMIN,
        ]);

        if (! $isOwner && ! $isPrivileged) {
            abort(403);
        }
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
