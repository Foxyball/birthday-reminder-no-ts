# Project Guidelines

## Code Style

- PHP code follows PSR-4 autoloading (see [composer.json](composer.json)).
- Use 4 spaces for indentation, LF line endings, and UTF-8 encoding ([.editorconfig](.editorconfig)).
- Run `vendor/bin/pint` to auto-format PHP before finalizing changes.
- Check sibling files for structure and naming before creating new files.

## Build and Test

- Install dependencies: `composer install` and `npm install`.
- Build frontend: `npm run build` or `npm run dev` (Vite). Rebuild after every JS/CSS change.
- Run tests: `php artisan test --compact` (Pest). Create tests: `php artisan make:test --pest {Name}`.
- Do not create verification scripts if tests exist.

## Architecture

Laravel 12 app with Yajra DataTables, Alpine.js, and Tailwind CSS (class-based dark mode).

- **Admin routes** are prefixed `/bgc/`, grouped under `['auth', 'admin']` middleware in [routes/web.php](routes/web.php). The `admin` middleware checks `users.role === 1` ([app/Http/Middleware/EnsureAdmin.php](app/Http/Middleware/EnsureAdmin.php)).
- **Locale** is set per-session via `SetLocale` middleware; switch with `route('lang.switch', $locale)`.

## CRUD Pattern (follow exactly for new resources)

Each resource requires these files — use [app/DataTables/CategoryDataTable.php](app/DataTables/CategoryDataTable.php) and [app/Http/Controllers/CategoryController.php](app/Http/Controllers/CategoryController.php) as the canonical reference:

1. **`app/DataTables/{Name}DataTable.php`** — defines columns, renders action buttons and status toggles as raw HTML, reinit Alpine via the global `draw.dt` handler.
2. **`app/Http/Controllers/{Name}Controller.php`** — injects the DataTable into `index()`, uses route-model binding for `edit`/`update`/`destroy`, declares message key constants at the top.
3. **`app/Http/Requests/Store{Name}Request.php`** — single FormRequest reused for both store and update.
4. **`resources/views/{name}/index|create|edit.blade.php`** — extends `layouts.master_dashboard`.

Register a custom `change-status` PUT route **before** the resource route (so it isn't shadowed):
```php
Route::put('/bgc/{name}/change-status', ...)->name('admin.{name}.change-status');
Route::resource('/bgc/{name}', ...);
```

## DataTable Conventions

- Status toggle column: rendered as Alpine `x-data` HTML inside the DataTable class; `change-status` class on the checkbox triggers the global handler in [resources/js/global-admin.js](resources/js/global-admin.js).
- Pass the status URL via `data-status-url` on the table element: `$dataTable->table(['data-status-url' => route('admin.{name}.change-status')])`.
- Delete links use class `delete-item` with the destroy route as `href`; the global SweetAlert2 handler fires a DELETE AJAX call and expects `{ status: 'success'|'error', message: '...' }`.
- After a successful delete, the DataTable auto-reloads via `.ajax.reload(null, false)`.
- After each `draw.dt` event, Alpine is reinitialised via `handleDataTableAlpineReinit()` — required because Alpine doesn't observe dynamically injected DataTable rows.

## JS / Frontend

- [resources/js/global-admin.js](resources/js/global-admin.js) provides reusable handlers: `showToast`, `closeToast`, `handleDeleteDelegation`, `handleStatusToggle`, `handleDataTableAlpineReinit`.
- Dark mode: Alpine toggles a `dark` class on `<body>` (persisted to `localStorage`). Tailwind uses `darkMode: 'class'` ([tailwind.config.js](tailwind.config.js)).
- DataTables CDN CSS does not support dark mode — overrides live in [resources/views/partials/stylesheets.blade.php](resources/views/partials/stylesheets.blade.php).

## Translations & Flash Messages

- All UI strings use `__('messages.key')`. Add keys to both [resources/lang/en/messages.php](resources/lang/en/messages.php) and [resources/lang/bg/messages.php](resources/lang/bg/messages.php).
- Controllers define translation key constants (`const SUCCESS_MESSAGE = 'messages.xxx'`) and redirect with `->with('status', __(self::SUCCESS_MESSAGE))`.
- JS success/error feedback uses `showToast('success'|'error', message)`.

## Helpers

- `SidebarHelper::setActive(['route.name'])` returns `menu-item-active` or `menu-item-inactive` for sidebar links.
- Models with a `name` field auto-generate `slug` via `Str::slug($data['name'])` in store and update.

## Skills

Detailed, example-driven references for common tasks:
- **Controllers** — [.github/skills/controllers/SKILL.md](.github/skills/controllers/SKILL.md): full controller pattern with annotated example, route registration, FormRequest, and new-resource checklist.
- **Global Admin JS** — [.github/skills/global-admin/SKILL.md](.github/skills/global-admin/SKILL.md): all AJAX patterns (delete, status toggle, toasts, Alpine reinit). Do not write inline AJAX in Blade views.

## Constraints

- Do not add new base folders or dependencies without approval.
- Do not create documentation files unless explicitly requested.
