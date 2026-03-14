<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $role = trim((string) $request->query('role', ''));

        $query = User::query()->select(['id', 'name', 'email', 'role', 'created_at']);

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        return view('admin.users.index', [
            'users' => $query->latest()->paginate(20)->withQueryString(),
            'search' => $search,
            'selectedRole' => $role,
            'roles' => [
                User::ROLE_USER,
                User::ROLE_REVIEWER,
                User::ROLE_ADMIN,
                User::ROLE_SUPERADMIN,
            ],
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:user,reviewer,admin,superadmin'],
        ]);

        if ((int) $request->user()->id === (int) $user->id && $data['role'] !== User::ROLE_SUPERADMIN) {
            return back()->withErrors([
                'role' => 'You cannot downgrade your own superadmin account.',
            ]);
        }

        $user->update([
            'role' => $data['role'],
        ]);

        return back()->with('status', 'User role updated successfully.');
    }
}
