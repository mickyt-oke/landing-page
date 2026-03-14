<?php

namespace App\Http\Requests\Web;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StartReviewRequest extends FormRequest
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
            User::ROLE_REVIEWER,
            User::ROLE_ADMIN,
            User::ROLE_SUPERADMIN,
        ]) && $application->status === Application::STATUS_SUBMITTED;
    }

    public function rules(): array
    {
        return [];
    }
}
