# Laravel To-Do List Application

A modern, responsive To-Do List web application built with Laravel 12 and Tailwind CSS 4. This application provides a clean, intuitive interface for managing daily tasks with full CRUD functionality, advanced filtering, and a trash system for deleted tasks.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-4.x-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-purple.svg)
![SQLite](https://img.shields.io/badge/Database-SQLite-green.svg)

## Features

### 🎯 Core Functionality

-   **Full CRUD Operations**: Create, read, update, and delete tasks
-   **Task Properties**: Title, description, due date, and completion status
-   **Smart Sorting**: Tasks automatically sorted by due date
-   **Status Grouping**: Separate sections for pending and completed tasks
-   **Overdue Highlighting**: Overdue tasks are visually highlighted in red

### 🎨 User Experience

-   **Responsive Design**: Optimized for both desktop and mobile devices
-   **Clean UI**: Modern interface using Tailwind CSS
-   **Modal Editing**: Edit tasks in convenient modal dialogs
-   **One-Click Toggle**: Quick completion status changes
-   **Visual Feedback**: Hover effects and smooth transitions

### 🔍 Advanced Features

-   **Search Functionality**: Search tasks by title and description
-   **Smart Filtering**: Filter by status (pending/completed) and date ranges
-   **Soft Deletes**: Deleted tasks move to trash instead of permanent deletion
-   **Trash Management**: Restore or permanently delete trashed tasks
-   **Task Statistics**: Overview of total, pending, completed, and overdue tasks

### 🛡️ Technical Features

-   **Form Validation**: Custom validation with user-friendly error messages
-   **CSRF Protection**: Built-in security against cross-site request forgery
-   **Flash Messages**: Success and error notifications
-   **Reusable Components**: Modular Blade components for consistent UI
-   **MVC Architecture**: Clean separation of concerns following Laravel best practices

## Requirements

-   PHP 8.2 or higher
-   Composer
-   Node.js 20.19+ or 22.12+ (for asset compilation)
-   SQLite (default) or MySQL
-   Git

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd todo-laravel
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Setup

```bash
# Create SQLite database (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed with sample data (optional)
php artisan db:seed
```

### 6. Build Assets

```bash
# For development
npm run dev

# For production
npm run build
```

### 7. Start the Application

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

## Usage

### Creating Tasks

1. Click "Add New Task" button
2. Fill in the title (required)
3. Optionally add description and due date
4. Click "Create Task"

### Managing Tasks

-   **Complete/Uncomplete**: Click the checkbox next to any task
-   **Edit**: Click the edit icon to modify task details
-   **Delete**: Click the trash icon to move task to trash

### Filtering and Search

-   Use the search bar to find tasks by title or description
-   Filter by status: All, Pending, or Completed
-   Filter by date: Today, This Week, or Overdue tasks
-   Clear filters to return to full view

### Trash Management

1. Navigate to "Trash" in the top navigation
2. View all deleted tasks
3. Restore tasks by clicking "Restore"
4. Permanently delete with "Delete Forever"

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── TaskController.php      # Main task controller
│   └── Requests/
│       └── TaskRequest.php         # Form validation
├── Models/
│   └── Task.php                    # Task model with business logic
database/
├── factories/
│   └── TaskFactory.php             # Factory for generating test data
├── migrations/
│   └── create_tasks_table.php      # Database schema
└── seeders/
    └── TaskSeeder.php              # Sample data seeder
resources/
├── css/
│   └── app.css                     # Main stylesheet with Tailwind
├── js/
│   └── app.js                      # JavaScript entry point
└── views/
    ├── components/                 # Reusable Blade components
    │   ├── button.blade.php
    │   ├── modal.blade.php
    │   └── task-card.blade.php
    ├── layouts/
    │   └── app.blade.php           # Main layout template
    └── tasks/                      # Task-specific views
        ├── index.blade.php         # Main dashboard
        ├── create.blade.php        # Task creation form
        └── trash.blade.php         # Trash management
routes/
└── web.php                         # Application routes
```

## API Routes

| Method | URI                        | Action                     | Description        |
| ------ | -------------------------- | -------------------------- | ------------------ |
| GET    | `/`                        | TaskController@index       | Redirect to tasks  |
| GET    | `/tasks`                   | TaskController@index       | List all tasks     |
| GET    | `/tasks/create`            | TaskController@create      | Show create form   |
| POST   | `/tasks`                   | TaskController@store       | Store new task     |
| GET    | `/tasks/{task}`            | TaskController@show        | Show single task   |
| GET    | `/tasks/{task}/edit`       | TaskController@edit        | Show edit form     |
| PUT    | `/tasks/{task}`            | TaskController@update      | Update task        |
| DELETE | `/tasks/{task}`            | TaskController@destroy     | Soft delete task   |
| PATCH  | `/tasks/{task}/toggle`     | TaskController@toggle      | Toggle completion  |
| GET    | `/trash`                   | TaskController@trash       | List trashed tasks |
| PATCH  | `/trash/{id}/restore`      | TaskController@restore     | Restore task       |
| DELETE | `/trash/{id}/force-delete` | TaskController@forceDelete | Permanent delete   |

## Configuration

### Environment Variables

```env
# Database Configuration (SQLite example)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# MySQL Configuration (alternative)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_app
DB_USERNAME=root
DB_PASSWORD=
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

Built with ❤️ using Laravel and Tailwind CSS

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
