<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_admin_dashboard(): void
    {
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_reviewer_can_access_admin_dashboard(): void
    {
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $reviewer */
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);

        $this->actingAs($reviewer)
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_reviewer_can_start_review_for_submitted_application(): void
    {
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $reviewer */
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $owner */
        $owner = User::factory()->create(['role' => User::ROLE_USER]);

        $application = Application::query()->create([
            'user_id' => $owner->id,
            'application_reference' => 'APP-TEST-0001',
            'full_name' => 'John Doe',
            'passport_number' => 'A1234567',
            'nationality' => 'Nigerian',
            'visa_category' => 'Tourist',
            'arrival_date' => now()->subDays(10)->toDateString(),
            'overstay_days' => 10,
            'status' => Application::STATUS_PENDING,
        ]);

        $this->actingAs($reviewer)
            ->post('/admin/applications/'.$application->id.'/start-review')
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => Application::STATUS_UNDER_REVIEW,
        ]);
    }

    public function test_reviewer_cannot_approve_application(): void
    {
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $reviewer */
        $reviewer = User::factory()->create(['role' => User::ROLE_REVIEWER]);
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $owner */
        $owner = User::factory()->create(['role' => User::ROLE_USER]);

        $application = Application::query()->create([
            'user_id' => $owner->id,
            'application_reference' => 'APP-TEST-0002',
            'full_name' => 'Jane Doe',
            'passport_number' => 'B1234567',
            'nationality' => 'Nigerian',
            'visa_category' => 'Business',
            'arrival_date' => now()->subDays(5)->toDateString(),
            'overstay_days' => 5,
            'status' => Application::STATUS_UNDER_REVIEW,
        ]);

        $this->actingAs($reviewer)
            ->post('/admin/applications/'.$application->id.'/approve', [
                'reviewer_comment' => 'Looks good.',
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_approve_under_review_application(): void
    {
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $admin */
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $owner */
        $owner = User::factory()->create(['role' => User::ROLE_USER]);

        $application = Application::query()->create([
            'user_id' => $owner->id,
            'application_reference' => 'APP-TEST-0003',
            'full_name' => 'Approved User',
            'passport_number' => 'C1234567',
            'nationality' => 'Nigerian',
            'visa_category' => 'Student',
            'arrival_date' => now()->subDays(7)->toDateString(),
            'overstay_days' => 7,
            'status' => Application::STATUS_UNDER_REVIEW,
        ]);

        $this->actingAs($admin)
            ->post('/admin/applications/'.$application->id.'/approve', [
                'reviewer_comment' => 'Approved by admin.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => Application::STATUS_APPROVED,
            'reviewed_by' => $admin->id,
        ]);
    }

    public function test_admin_cannot_approve_submitted_application_due_to_transition_rule(): void
    {
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $admin */
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $owner */
        $owner = User::factory()->create(['role' => User::ROLE_USER]);

        $application = Application::query()->create([
            'user_id' => $owner->id,
            'application_reference' => 'APP-TEST-0004',
            'full_name' => 'Transition User',
            'passport_number' => 'D1234567',
            'nationality' => 'Nigerian',
            'visa_category' => 'Work',
            'arrival_date' => now()->subDays(3)->toDateString(),
            'overstay_days' => 3,
            'status' => Application::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->post('/admin/applications/'.$application->id.'/approve', [
                'reviewer_comment' => 'Should fail.',
            ])
            ->assertStatus(403);
    }

    public function test_superadmin_can_manage_users_page_but_admin_cannot(): void
    {
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $superadmin */
        $superadmin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $admin */
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($superadmin)
            ->get('/admin/users')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertStatus(403);
    }

    public function test_superadmin_can_update_user_role(): void
    {
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $superadmin */
        $superadmin = User::factory()->create(['role' => User::ROLE_SUPERADMIN]);
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $target */
        $target = User::factory()->create(['role' => User::ROLE_USER]);

        $this->actingAs($superadmin)
            ->patch('/admin/users/'.$target->id.'/role', [
                'role' => User::ROLE_REVIEWER,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'role' => User::ROLE_REVIEWER,
        ]);
    }
}
