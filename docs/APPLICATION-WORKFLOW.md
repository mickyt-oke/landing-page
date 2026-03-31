# Application Documentation

## Overview
This is a Laravel-based web application for managing immigration/overstay regularization applications (likely for Nigerian Immigration Service - NIS). Users can register/login, submit applications with documents, track status. Admins/reviewers manage reviews/approvals/rejections. Features role-based access control (RBAC), file uploads, API status checks, and dashboard views.

Key technologies: Laravel 11, Eloquent ORM, Blade templating, JWT for API auth, file storage.

Database: MySQL (xampp default), tables for `users` (with roles), `applications` (statuses, details), `application_documents`.

## User Management
### Roles (defined in `App\\Models\\User`)
- **user**: Regular applicants. Can create/submit/track applications.
- **reviewer**: Can view dashboard, start reviews (pending → under_review).
- **admin**: Can approve/reject under_review applications.
- **superadmin**: All admin powers + manage users (view list, update roles).

### Role Methods (User model)
```php
public const ROLE_USER = 'user';
public const ROLE_REVIEWER = 'reviewer';
public const ROLE_ADMIN = 'admin';
public const ROLE_SUPERADMIN = 'superadmin';

isUser(), isReviewer(), isAdmin(), isSuperAdmin(), hasAnyRole(array \$roles)
```

### Authentication
- **Web**: Session-based (login/register via LandingController).
- **API**: JWT (Tymon\\JWTAuth) via AuthController (register/login/me/logout/refresh/update-profile/change-password).
- Middleware: `auth` for user routes, `role:roles` via `EnsureUserHasRole`.

### User Fields
`name, surname, first_name, other_names, passport_number, passport_type, nationality, email, password, role`

Superadmin manages users at `/admin/users` (search/filter by role, patch role).

Seeders: `DemoUsersSeeder.php` for testing roles.

## Application Workflow
1. **Submit Application** (`/applications/create` → POST `/applications`):
   - Auth user fills form (prefill from profile: surname/first_name/etc.).
   - Required: full_name, passport_number, nationality, visa_category, arrival_date, address/city/state, applicant_note.
   - Auto: reference/ack_ref_number (random 10-digit), overstay_days (arrival_date diff today), status=pending, submitted_at=now.
   - Upload docs: passport_data_page, entry_visa, entry_stamp, return_ticket (stored in `storage/app/applications/{id}/`).
   - Redirect to success/acknowledgement.

2. **Status Transitions** (statuses in `App\\Models\\Application`):
   | Status         | Description                  | Action By     |
   |----------------|------------------------------|---------------|
   | pending       | Newly submitted             | -             |
   | under_review  | Reviewer started review     | reviewer      |
   | approved      | Approved w/ comment         | admin+        |
   | rejected      | Rejected w/ reason + comment| admin+        |

3. **Review Process** (admin dashboard `/admin/dashboard`):
   - Filter by status, paginated list w/ user/reviewer info.
   - POST start-review: pending → under_review.
   - POST approve: under_review → approved (needs comment), set reviewed_by/at.
   - POST reject: under_review → rejected (needs reason + comment).

4. **Track Status**:
   - User views: `/applications/{id}/success`, `/applications/{application}/acknowledgement`.
   - API: POST `/api/status/check` (public, by reference/ack_ref → JSON status/name/reference/dates).
   - Access: Owner or privileged role (reviewer/admin/superadmin).

5. **Documents**:
   - Upload on submit, view/download at `/applications/{id}/documents`.
   - Model: ApplicationDocument (type, original/stored_name, path, mime/size).

Constraints: Controllers enforce ownership/roles (authorizeApplicationAccess), transition rules (e.g., can't approve pending).

## Pages and Features
### Public (no auth)
- `/` (home): index.blade.php - Landing page w/ modals (login/register/eligibility/status).
- `/faq`: FAQ page.
- `/login`: Shows landing (modal login).

### Authenticated User (`middleware('auth')`)
| Route                          | Controller/Action     | View                    | Description                  |
|--------------------------------|-----------------------|-------------------------|------------------------------|
| `/dashboard`                  | DashboardController@index | dashboard.blade.php    | User dashboard               |
| `/applications/create`        | ApplicationController@create | applications/create.blade.php | Form w/ prefill, nationalities JSON |
| POST `/applications`          | ApplicationController@store | -                      | Submit app + docs            |
| `/applications/{id}/success`  | ApplicationController@show | applications/success.blade.php | Success view w/ docs |
| `/applications/{id}/acknowledgement` | @acknowledgement | applications/acknowledgement.blade.php | Print/download ack |
| `/applications/{id}`          | @show                | applications/success.blade.php | App details |
| `/applications/{id}/documents`| @documents           | ?                      | Doc list/upload?             |
| GET doc download              | @downloadDocument    | -                      | Secure download              |

### Admin Routes (`/admin/*`)
| Route (prefixed web.admin.) | Middleware              | Description                  |
|-----------------------------|-------------------------|------------------------------|
| `/admin/dashboard`         | role:reviewer,admin,superadmin | AppReviewController@index - Review dashboard |
| POST app/start-review      | reviewer+              | Start review                 |
| POST app/approve           | admin+                 | Approve                      |
| POST app/reject            | admin+                 | Reject                       |
| `/users` (index)           | superadmin             | UserManagementController@index - User list/search/filter |
| PATCH `/users/{id}/role`   | superadmin             | Update role                  |

### Blade Layouts
- Most: `@include('partials.header')` + content + `@include('partials.footer')`.
- Admin users: `@extends('dashboard')`.

### Other Features
- **Data**: JSON assets/countries/nationalities/states_cities.
- **Tests**: RoleWorkflowTest.php (access/transition validation).
- **API**: Auth endpoints + status/check.
- **Assets**: CSS/JS for form/dashboard/carousel, NIS images/logos.

## Deployment/Run
- XAMPP (htdocs/landing-page), `php artisan serve` or Apache.
- `php artisan migrate --seed` (DemoUsersSeeder).
- Storage link: `php artisan storage:link`.

For changes, see TODO.md/controllers/views/migrations.

