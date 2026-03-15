# TODO

- [x] Add DB migration to make application workflow default status `pending` (and include status value in enum).
- [x] Update application domain logic to use `pending` as initial status for new submissions.
- [x] Ensure new user registration assigns default role `user`.
- [x] Add web login handler (email/password) that authenticates and redirects to `/dashboard`.
- [x] Wire login route in `routes/web.php`.
- [ ] Run quick verification checks (syntax/tests if available).
- [x] Mark all completed items.
