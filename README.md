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

- PHP 8.2+ (Run locally)
- Composer (Run locally)
- Node.js & npm (Run locally)
- Docker & Docker Compose (Run with Docker)

### Installation

#### Standard Setup
1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/school-management.git
    cd school-management
    ```

#### Docker Setup (Recommended)
1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/school-management.git
    cd school-management
    ```
2.  **Environment Setup**
    ```bash
    cp .env.example .env
    ```
    Configure `.env` to use the Docker database credentials:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=laravel
    DB_PASSWORD=root
    
    REDIS_HOST=redis
    ```
3.  **Start Containers**
    ```bash
    docker-compose -f docker-compose.dev.yml up -d --build
    ```
4.  **Install Dependencies**
    ```bash
    docker-compose -f docker-compose.dev.yml exec app composer install
    docker-compose -f docker-compose.dev.yml exec app php artisan key:generate
    docker-compose -f docker-compose.dev.yml exec app php artisan migrate
    ```
5.  **Access Application**
    Visit `http://localhost:8000`

### Standard Setup (Alternative)

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

    **Required Environment Variables (Add to .env):**
    ```ini
    STRIPE_KEY=pk_test_...
    STRIPE_SECRET=sk_test_...
    STRIPE_WEBHOOK_SECRET=whsec_...
    ```

    > **Security Note**: Never commit your real `.env` file or API keys. For production, use a secure secrets manager like GitHub Secrets, Laravel Vapor, or Laravel Forge to inject these values.

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
