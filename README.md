# DevConnect API

<p align="center">
  <img src="./public/media/README.md" alt="DevConnect Logo" width="180"/>
</p>

> A modern social networking REST API built with Laravel, JWT Authentication, and MySQL.

---

## Overview

DevConnect is a full-stack social networking platform developed as a portfolio project.

The backend exposes a RESTful API responsible for user authentication, profile management, posts, comments, likes, friendships, media uploads and all business rules of the application.

The frontend consumes this API using Vanilla JavaScript.

---

## Features

- JWT Authentication
- User Registration & Login
- User Profiles
- Avatar Upload
- Cover Upload
- Create Posts
- Comments
- Likes
- Friends System
- User Search
- Photo Gallery
- RESTful API
- MySQL Database

---

## Tech Stack

- PHP 8.5
- Laravel 13
- MySQL
- JWT Authentication
- Eloquent ORM
- Vanilla JavaScript
- HTML5
- CSS3

---

## Project Architecture

```
Frontend (Vanilla JS)
        │
        │ HTTP / JSON
        ▼
Laravel REST API
        │
        ▼
   Eloquent ORM
        │
        ▼
      MySQL
```

---

## Installation

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

Generate JWT secret

```bash
php artisan jwt:secret
```

Run migrations

```bash
php artisan migrate
```

Start the development server

```bash
php artisan serve
```

---

## Authentication

The API uses JWT Authentication.

Protected routes require:

```
Authorization: Bearer YOUR_TOKEN
```

---

## Future Improvements

- Notifications
- Real-time chat
- Groups
- Stories
- Infinite scroll
- Password recovery
- Email verification
- Docker support
- Automated tests

---

## Author

**Vinicius Marcondes Bacca**

Software Developer | Backend (.NET & Laravel) | QA Automation

GitHub:
https://github.com/VMBacca

LinkedIn:
https://www.linkedin.com/in/viniciusmarcondesbacca/

---

## License

This project is licensed under the MIT License.
