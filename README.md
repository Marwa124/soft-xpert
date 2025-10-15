<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


Project Overview
Softxpert is a Laravel-based Restful API for managing user Tasks with authentication and authorization.

Features
Authentication & User Management
JWT Authentication - Secure API authentication

Technical Features
Database Migrations & Seeders - Structured database setup

Laravel ORM - Eloquent relationships between entities

JWT Auth - JSON Web Token Authentication

MySQL - Database

Installation & Setup
# Prerequisites
PHP 8.1 or higher

Composer

MySQL Database

# Step 1: Clone and Install Dependencies
bash
## Clone the repository
git clone https://github.com/Marwa124/soft-xpert.git

cd soft-xpert

## Copy environment file
cp .env.example .env

# Step 2: Run Installation Command

The project includes a custom artisan
command that handles all setup automatically:

bash
```bash
php artisan install-project
```

This command will:

Composer Installation

Run database migrations

Seed initial data

Generate JWT secret

## Option 1: 
Run 
```bash
php artisan serve
```

## Option 2: 
Docker 
```bash
sudo docker compose up -d --build
```

