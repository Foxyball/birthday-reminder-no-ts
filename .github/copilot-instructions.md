# Project Guidelines

## Code Style
- PHP code follows PSR-4 autoloading (see [composer.json](composer.json)).
- Use 4 spaces for indentation, LF line endings, and UTF-8 encoding ([.editorconfig](.editorconfig)).
- Run `vendor/bin/pint` to auto-format code before finalizing changes.
- Use descriptive variable and method names (e.g., `isRegisteredForDiscounts`).
- Check sibling files for structure and naming before creating new files.
- For Tailwind CSS, follow existing utility class patterns in [resources/views/](resources/views/).

## Architecture
- Follows standard Laravel 12 structure: [app/Http/Controllers](app/Http/Controllers), [app/Models](app/Models), [resources/views](resources/views), [app/Helpers](app/Helpers).
- CRUD operations use DataTables for listing and filtering (see controller/view patterns).
- Service boundaries: Controllers handle HTTP, Models handle data, Views handle presentation, Helpers provide shared logic.

## Build and Test
- Install dependencies: `composer install` and `npm install`.
- Build frontend: `npm run build` or `npm run dev`.
- Run tests: `php artisan test --compact` (uses Pest).
- Create tests: `php artisan make:test --pest {Name}`.
- Do not create verification scripts if tests exist.

## Project Conventions
- Stick to the existing directory structure; do not create new base folders without approval.
- CRUDs use DataTables for index/listing pages.
- Always check for reusable components before creating new ones.
- Do not change dependencies without approval.
- Documentation files should only be created if explicitly requested.

## Integration Points
- Uses Laravel packages via Composer (see [composer.json](composer.json)).
- Uses Tailwind CSS for styling ([tailwind.config.js](tailwind.config.js)).
- Uses Livewire for reactive components.
- No external APIs are referenced by default; check for integration logic in [app/Helpers](app/Helpers) and [config/](config/).

## Security
- Authentication uses Laravel's default session guard ([config/auth.php](config/auth.php)).
- User model: [app/Models/User.php](app/Models/User.php), migration: [database/migrations/0001_01_01_000000_create_users_table.php](database/migrations/0001_01_01_000000_create_users_table.php).
- Sensitive actions are protected by middleware and policies (see [app/Http/Middleware](app/Http/Middleware)).
- Passwords are hashed using Laravel's built-in mechanisms.

---

If any section is unclear or incomplete, please provide feedback for further refinement.