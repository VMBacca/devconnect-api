<p align="center">
  <img src="docs/images/devconnect_logo.png" alt="DevConnect API" width="220">
</p>

<h1 align="center">DevConnect API</h1>

<p align="center">
RESTful API for the DevConnect social networking application, built with Laravel, MySQL and JWT Authentication.
</p>

<p align="center">

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-Authentication-000000?style=for-the-badge&logo=jsonwebtokens)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![REST API](https://img.shields.io/badge/REST-API-6DB33F?style=for-the-badge)

</p>

> REST API developed as the backend for the DevConnect social networking platform.

---

# 📖 About the Project

DevConnect API is a RESTful backend designed to power a modern social networking application, providing secure authentication, user management, media uploads and social interactions through a clean JSON API.

The application communicates with the DevConnect Frontend through JWT-secured endpoints, following a clear separation between frontend presentation and backend business logic.

The API communicates with the DevConnect Frontend through JSON endpoints secured using JWT Authentication, following a clear separation between frontend presentation and backend business logic.

---

# ✨ Highlights

- JWT Authentication
- RESTful API
- Media Uploads
- Social Feed
- User Profiles
- Search System
- MySQL Database
- Clean REST Architecture

---

# ✨ Features

## Authentication

- User registration
- Secure login with JWT Authentication
- Protected API routes
- Persistent authenticated sessions

## User Management

- User profile
- Avatar upload
- Cover upload
- User search
- Followers & Following

## Feed

- Create text posts
- Upload photo posts
- Delete own posts
- Chronological feed

## Social Features

- Like posts
- Comment on posts
- User relationships

## Media

- Personal gallery
- Photo uploads

---

# 🛠 Tech Stack

### Backend

- PHP 8.5
- Laravel 13
- JWT Authentication (tymon/jwt-auth)
- MySQL
- RESTful API

### Tools

- Composer
- Artisan
- XAMPP

---

## Architecture

The project follows a layered architecture, separating authentication, business rules, media handling and API endpoints through Laravel controllers, models and services.

---

# 📁 Project Structure

```text
app/
├── Http/
├── Models/

bootstrap/
config/
database/
public/
resources/
routes/
storage/

artisan
composer.json
README.md
```

---

# Requirements

- PHP 8.5+
- Composer 2+
- MySQL

---

# 🚀 Getting Started

Clone the repository

```bash
git clone https://github.com/VMBacca/devconnect-api.git
```

Install dependencies

```bash
composer install
```

> **Note:** This project requires **PHP 8.5+**.

Create the environment file

```bash
cp .env.example .env
```

Configure your database credentials in the `.env` file.

Generate the application key

```bash
php artisan key:generate
```

Generate the JWT secret

```bash
php artisan jwt:secret
```

Run the migrations

```bash
php artisan migrate
```

Create the storage symbolic link

```bash
php artisan storage:link
```

Start the development server

```bash
php artisan serve
```

If you experience compatibility issues with the Laravel development server on Windows environments, you can alternatively start the application using the built-in PHP server:

```bash
php -S 127.0.0.1:8000 -t public
```

The API will be available at:

```text
http://127.0.0.1:8000
```

---

# 📡 Main Endpoints

| Method | Endpoint                 | Description           |
| :----: | ------------------------ | --------------------- |
|  POST  | `/api/register`          | Register a new user   |
|  POST  | `/api/login`             | User authentication   |
|  GET   | `/api/feed`              | Get user feed         |
|  POST  | `/api/feed`              | Create a new post     |
|  POST  | `/api/post/{id}/like`    | Like or unlike a post |
|  POST  | `/api/post/{id}/comment` | Add a comment         |
|  GET   | `/api/user`              | Logged user profile   |
|  GET   | `/api/user/{id}`         | User profile          |
|  GET   | `/api/search`            | Search users          |

---

# 🔗 Related Project

➡️ **DevConnect Frontend**

Repository:
https://github.com/VMBacca/devconnect-frontend

---

# 🗺 Roadmap

Planned improvements for future versions:

### Social

- Friend request system
- Accept / Reject requests
- User notifications
- Direct messaging

### Security

- Refresh Tokens
- Password recovery
- Email verification
- Better authorization policies

### API

- Swagger / OpenAPI documentation
- Automated tests
- Request validation improvements
- Rate limiting
- API versioning

### Infrastructure

- Docker Compose
- Docker support
- CI/CD pipeline
- Redis cache
- Queue system

---

# 👨‍💻 Author

**Vinicius Marcondes Bacca**

Backend Developer (.NET) | REST APIs | Laravel | QA Automation

- GitHub: https://github.com/VMBacca
- LinkedIn: https://www.linkedin.com/in/viniciusmarcondesbacca/

---

# 📄 License

This project was developed for educational purposes and as part of my software development portfolio.
