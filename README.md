# Blog Project

This is a simple blog application built using modern web technologies. The project serves as a demonstration of how to create a clean and modular web application using Symfony, Tailwind CSS, and Vanilla JavaScript.

## 🚀 Technologies Used

- **Symfony** – A powerful PHP framework for building web applications with a structured and reusable codebase.
- **Tailwind CSS** – A utility-first CSS framework that allows for rapid UI development with a modern look.
- **Vanilla JavaScript** – Used for adding interactivity and handling frontend behaviors without relying on heavy JS frameworks.

## 📁 Structure Overview

- `src/` – Contains the core PHP logic (Controllers, Entities, etc.)
- `templates/` – Contains Twig templates used for rendering views.
- `public/` – Public-facing files (like JavaScript, images, compiled CSS).
- `assets/` – Contains raw JS and CSS files for development.
- `.env` – Environment variables and database configuration.

## 🛠 Setup Instructions

1. Clone the repository.
2. install `composer` to install PHP dependencies.
3. Set your `.env` database credentials.
4. Create the database using:
`php bin/console doctrine:database:create`
