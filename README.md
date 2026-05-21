# Task Manager Pro - Full Stack Kanban Application

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

A modern, enterprise-level Task Management application built with **Laravel**, **Vue 3**, and **Tailwind CSS**. Designed with a developer-first approach, featuring a native Drag-and-Drop Kanban board, real-time filtering, and full dark mode support.

## Key Features

* **Kanban Board:** Native HTML5 Drag-and-Drop functionality to move tasks between 'To Do', 'In Progress', and 'Done' stages.
* **Dark/Light Mode:** Seamless theme switching utilizing Tailwind CSS v4 custom variants.
* **Smart Search & Filters:** Real-time client-side filtering via Vue computed properties without overloading the backend API.
* **Inline Updates:** Edit task due dates directly from the task card for a frictionless UX.
* **Interactive UI:** Toast notifications (Vue3-Toastify) integrated with Lucide Icons for dynamic user feedback.
* **RESTful API:** Robust backend architecture built with Laravel, including strict validation and resource routing.

## Screenshots

<img width="1469" height="916" alt="Screenshot 2026-05-22 044100" src="https://github.com/user-attachments/assets/8ede713d-723f-409c-9e83-86832f66bb8f" />

<img width="1467" height="902" alt="Screenshot 2026-05-22 044154" src="https://github.com/user-attachments/assets/cf3147dd-d1c8-4eaf-ab41-acdb68e95739" />


## Tech Stack

* **Backend:** PHP, Laravel 11, MySQL
* **Frontend:** Vue 3 (Composition API), Vite, Tailwind CSS v4
* **Icons & Notifications:** Lucide Vue Next, Vue3-Toastify
* **Architecture:** Monolithic (Laravel + Vue integrated via Vite)

## Getting Started

Follow these steps to run the project locally on your machine.

### Prerequisites
* PHP >= 8.2
* Composer
* Node.js & npm
* MySQL (Laragon/XAMPP)

### Installation

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/Chathushka64/laravel-vue-task-manager.git](https://github.com/Chathushka64/laravel-vue-task-manager.git)
   cd laravel-vue-task-manager

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   Copy the `.env.example` file and rename it to `.env`.
   ```bash
   cp .env.example .env
   ```
   Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. **Database Configuration:**
   Create a database named `task_manager` in your MySQL server. Then update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_manager
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

7. **Start the Development Servers:**
   You will need two terminal windows to run both the backend and frontend asset bundler.
   
   Terminal 1 (Laravel Server):
   ```bash
   php artisan serve
   ```
   
   Terminal 2 (Vite Frontend):
   ```bash
   npm run dev
   ```

8. **Visit the App:** Open `http://127.0.0.1:8000` in your browser.

---
**Developed by Chathushka Madhusanka** - *Full Stack Developer*

