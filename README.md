# School Management System

A comprehensive School Management System built with Laravel 12, Inertia.js, and React.

## Features

- **Authentication**: Secure login and registration using Laravel Breeze.
- **Role-Based Access Control**: (Planned) Manage permissions for Admins, Teachers, Students, and Parents.
- **Student Management**: (Planned) Track student records, attendance, and grades.
- **Course Management**: (Planned) Organize classes, subjects, and schedules.

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: React, Inertia.js, Tailwind CSS
- **Database**: SQLite (Local), MySQL/PostgreSQL (Production)
- **Build Tool**: Vite

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm

### Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/school-management.git
    cd school-management
    ```

2.  **Install PHP dependencies**
    ```bash
    composer install
    ```

3.  **Install Node dependencies**
    ```bash
    npm install
    ```

4.  **Environment Setup**
    Copy the example environment file and configure it:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Note: The default setup uses SQLite. Ensure `DB_CONNECTION=sqlite` is set in `.env`.*

5.  **Run Migrations**
    ```bash
    php artisan migrate
    ```

6.  **Start Development Server**
    Start the Laravel server:
    ```bash
    php artisan serve
    ```
    In a separate terminal, start the Vite development server:
    ```bash
    npm run dev
    ```

## Running Tests

Run the PHPUnit tests:
```bash
php artisan test
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
