# User Authentication Implementation TODO

## Completed Tasks
- [x] Rename RegistrationController to AuthController and add login/logout methods
- [x] Update routes in routes/web.php to use AuthController and add login/logout routes
- [x] Create login.blade.php view with form for email, password
- [x] Create custom request classes: RegisterRequest and LoginRequest
- [x] Update AuthController to use custom request classes for validation
- [x] Run the Laravel application using `php artisan serve` (Server running on http://127.0.0.1:8000)

## Next Steps
- [ ] Test the registration form at /register
- [ ] Test the login form at /login
- [ ] Verify validation works for both forms (required fields, email format, password min 8 chars for registration, password confirmation)
- [ ] Check that user is created in database with hashed password during registration
- [ ] Ensure login authenticates users correctly and redirects to home
- [ ] Test logout functionality
- [ ] Ensure success/error messages appear appropriately
- [ ] Test error handling for invalid inputs on both forms
