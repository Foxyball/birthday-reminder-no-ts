---
name: controllers
description: This skill provides a set of controllers that can be used to manage and control various aspects of the system. It includes functionalities for handling user input, managing state, and coordinating interactions between different components.
---
Always check the [Project Guidelines](../copilot-instructions.md) for coding standards, architecture, and testing practices before making changes to the controllers.
For store and update operations, ensure that you are following the existing patterns for validation and data handling as seen in other controllers.
When creating new controllers, adhere to the PSR-4 autoloading standard and place them in the appropriate namespace under `app/Http/Controllers`.
Make use of Laravel's features such as middleware, request validation, and resource controllers to maintain consistency and leverage the framework's capabilities effectively.
Before finalizing changes, run `vendor/bin/pint` to auto-format your code and ensure it adheres to the project's coding standards. Additionally, create tests for any new functionality using Pest and run them to ensure your changes are covered and do not introduce regressions.
If you need to create new routes for your controllers, make sure to define them in the appropriate route files (e.g., `routes/web.php` or `routes/api.php`) and follow the existing routing conventions.
Always check for reusable components and services before creating new ones, and ensure that your controllers are focused on handling HTTP requests and delegating business logic to services or models as needed.
When working with views in your controllers, ensure that you are passing the necessary data and following the existing patterns for rendering views and handling responses.
If your controller interacts with models, make sure to use Eloquent relationships and query scopes where appropriate to keep your code clean and maintainable.
Finally, document any new controllers or methods you create with clear and concise comments to help other developers understand the purpose and functionality of your code.
For validation, create Form Request classes using `php artisan make:request {Name}Request` and use them in your controller methods to keep your controllers clean and maintainable. This also allows for better reusability of validation logic across different controllers.