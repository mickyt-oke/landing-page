<?php

namespace App\Http\Requests\Web;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ApproveApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var Application|null $application */
        $application = $this->route('application');

        if (! $user || ! $application) {
            return false;
        }

        return $user->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_SUPERADMIN,
        ]) && $application->status === Application::STATUS_UNDER_REVIEW;
    }

    public function rules(): array
    {
        return [
            'reviewer_comment' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
