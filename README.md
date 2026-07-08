<p align="center">
  <img src="docs/images/devconnect_logo.png" alt="DevConnect API" width="220">
</p>

<h1 align="center">DevConnect API</h1>

<p align="center">
RESTful API for the DevConnect social networking application, built with Laravel, MySQL and JWT Authentication.
</p>

<p align="center">

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-Authentication-000000?style=for-the-badge&logo=jsonwebtokens)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![REST API](https://img.shields.io/badge/REST-API-6DB33F?style=for-the-badge)

</p>

---

# 📖 About the Project

DevConnect API is the backend of the DevConnect social networking application.

It exposes a RESTful API responsible for user authentication, profile management, social interactions, media uploads and feed management.

The API communicates with the DevConnect Frontend through JSON endpoints secured using JWT Authentication, following a clear separation between frontend presentation and backend business logic.

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

- PHP 8
- Laravel
- JWT Authentication
- MySQL
- RESTful API

### Tools

- Composer
- Artisan
- XAMPP

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

# 🚀 Getting Started

Clone the repository

```bash
git clone https://github.com/VMBacca/devconnect-api.git
```

Install dependencies

```bash
composer install
```

Create the environment file

```bash
cp .env.example .env
```

Generate the application key

```bash
php artisan key:generate
```

Generate the JWT secret

```bash
php artisan jwt:secret
```

Configure your database in the `.env` file.

Run the migrations

```bash
php artisan migrate
```

Start the development server

```bash
php artisan serve
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

### DevConnect Frontend

Frontend application for this API:

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

- Docker support
- CI/CD pipeline
- Redis cache
- Queue system

---

# 👨‍💻 Author

**Vinicius Marcondes Bacca**

Backend Developer (.NET) | Laravel | QA Automation

- GitHub: https://github.com/VMBacca
- LinkedIn: https://www.linkedin.com/in/viniciusmarcondesbacca/

---

# 📄 License

This project was developed for educational purposes and as part of my software development portfolio.
