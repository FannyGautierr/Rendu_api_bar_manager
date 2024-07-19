# Bar Manager Project

Made by : Fanny Gautier 

This project is a Bar Management System built using the Symfony framework with API Platform. It allows users to manage orders, drinks, and staff roles such as waiters and barmen.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP 8.0 or higher
- Composer
- A database system (MySQL, PostgreSQL, or SQLite)
- OpenSSL for generating JWT keys

## Installation

### Clone the Repository

```bash
git clone git@github.com:FannyGautierr/Rendu_api_bar_manager.git
cd bar-manager
```

### Install Dependencies

```bash
composer install
```

### Generate JWT Keys

Generate your JWT keys:

```bash
php bin/console lexik:jwt:generate-keypair
```

### Environment Configuration

Create a `.env.local` file to override any default configuration:

```bash
cp .env .env.local
```

Edit the `.env.local` file to configure your environment variables:

```
# Example configurations
APP_ENV=dev
APP_SECRET=your_app_secret

DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name?serverVersion=8.0.32&charset=utf8mb4"
# Or for PostgreSQL
# DATABASE_URL="postgresql://username:password@127.0.0.1:5432/database_name?serverVersion=16&charset=utf8"

JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase

CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
```

### Database Setup

Create the database schema:

```bash
php bin/console doctrine:database:create
```
## Running the Application

### Development Server

To start the development server, run:

```bash
php bin/console server:run
```

Visit `http://localhost:8000` in your browser.

### API Documentation

API Platform provides an interactive API documentation. Visit `http://localhost:8000/api` to view the documentation and interact with your API.

## Postamn requests

You can find all the exemple requests of the api in the base folder :
```
/postman_request.json
```
