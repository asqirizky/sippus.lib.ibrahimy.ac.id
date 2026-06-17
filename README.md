<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project

This is a Laravel-based Attendance & Service Management system website used to manage:

- Manage Employees
- Set Schedules
- Daily attendance
- Monthly summary
- Permit requests
- Set holidays

The system is designed to display attendance data in a single summary table for easier analysis.

## Technologies Used

- php 8.4
- laravel
- MySql
- Docker

This project already supports containerization using Docker and Docker Compose.

## Installation Using Docker
Check Version
- docker -v
- docker compose Version

## Running Project
Open in terminal
1. Clone Repository
- git clone https://gitlab.com/asqirizky/presensi.git
- cd project-presensi
2. Copy File Environment
- cp .env.example .env

Adjust the database configuration: <br><br>
DB_CONNECTION=mysql <br>
DB_HOST=mysql <br>
DB_PORT=3306 <br>
DB_DATABASE=webperpus <br>
DB_USERNAME=root <br>
DB_PASSWORD=root <br>

## Running Docker

- docker compose up -d --build

Generate key & migrate database, log into the container :
- docker compose exec app bash

so run :
- php artisan key:generate
- php artisan migrate

## Access Aplication To :
http://localhost:8000

## Good Luck