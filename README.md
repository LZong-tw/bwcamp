# BWCamp (福智綜合資料庫)

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=flat-square&logo=vue.js&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-4.x-563D7C?style=flat-square&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)

BWCamp is a comprehensive camp management system designed to handle the complex logistics of organizing large-scale events. It streamlines volunteer management, student registration, and on-site operations.

## Table of Contents
- [Key Features](#key-features)
  - [Volunteer Management](#-volunteer-management)
  - [Student Registration](#-student-registration)
  - [System Administration](#-system-administration)
- [Tech Stack](#tech-stack)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Docker Development](#docker-development)
- [Usage](#usage)
  - [Common Commands](#common-commands)
- [License](#license)

## Key Features

### 👥 Volunteer Management
- **Registration & Recruitment**: Streamlined process for volunteer sign-ups.
- **Group Management**: Organize volunteers into specific job groups.
- **Check-in System**: On-site and course-specific check-in capabilities.

### 📝 Student Registration
- **Multi-channel Registration**: Supports general public registration and special invitation channels.
- **Automated Notifications**: Confirmation emails and participation tracking.
- **Grouping**: Automatic and manual grouping of students.

### ⚙️ System Administration
- **Camp Management**: Create and configure different types of camps (e.g., Teacher Camp, Youth Camp).
- **Permissions**: Role-based access control for different staff levels.
- **Reporting**: Real-time statistics on registration and attendance.

## Tech Stack

- **Backend**: Laravel 9 (PHP 8.0+)
- **Frontend**: Vue.js 3, Bootstrap 4, Tailwind CSS, jQuery
- **Database**: MySQL
- **Build Tool**: Vite

## Getting Started

### Prerequisites
- PHP >= 8.0
- Composer
- Node.js & NPM
- MySQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/LZong-tw/bwcamp.git
   cd bwcamp
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database settings in `.env`*

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Start Development Server**
   ```bash
   npm run dev
   php artisan serve
   ```

### Docker Development

If you prefer using Docker, you can use the provided `docker-compose.yml` file.

1. **Setup Environment**
   ```bash
   cp .env.example .env
   # Configure .env settings (DB_HOST=db, REDIS_HOST=redis, etc.)
   ```

2. **Start Containers**
   ```bash
   docker-compose up -d
   ```

3. **Install Dependencies & Setup**
   ```bash
   docker-compose exec app composer install
   docker-compose exec app npm install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   ```

4. **Access Application**
   The application will be available at `http://localhost`.

## Usage

### Common Commands

- **Clear Cache**:
  ```bash
  php artisan config:cache
  ```

- **Restart Queue Worker** (Required after code changes affecting queues):
  ```bash
  php artisan queue:restart
  ```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
