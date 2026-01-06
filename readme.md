# FPGS-DAW-DWES-SIMPLE

SportShop - A PHP e-commerce application for the Server-Side Web Development course (Desarrollo Web Entorno Servidor) as part of the DAW (Web Application Development) professional training program.

## Description

This is a full-stack e-commerce application built with PHP and MySQL, containerized with Docker for easy deployment. The project demonstrates MVC architecture, authentication, shopping cart functionality, and admin panel management.

## Course Information

- **Program**: FPGS DAW (Higher Level Professional Training - Web Application Development)
- **Year**: 2nd Year
- **Subject**: DWES (Desarrollo Web Entorno Servidor / Server-Side Web Development)

## Technologies

- PHP 8.4
- MySQL 8.0
- Apache
- Docker & Docker Compose
- HTML/CSS
- JavaScript

## Prerequisites

- Docker Desktop installed and running
- Git

## Installation

1. Clone this repository:
```bash
git clone https://github.com/adesduende/FPGS-DAW-DWES-SIMPLE.git
cd FPGS-DAW-DWES-SIMPLE
```

2. Create a `.env` file in the root directory with the following variables:
```env
DB_HOST=db
DB_PORT=3306
DB_NAME=SportShop
DB_USER=root
DB_PASSWORD=root
DB_ROOT_PASSWORD=root
```

3. Build and start the Docker containers:
```bash
docker compose up -d --build
```

4. Wait a few seconds for the database to initialize with seed data

## Usage

Access the application through your browser:
```
http://localhost
```

### Default Credentials

**Admin User:**
- Email: `admin@sportshop.com`
- Password: `admin123`

**Test User:**
- Email: `usuario@sportshop.com`
- Password: `admin123`

## Features

- User authentication and registration
- Product catalog with categories and filters
- Shopping cart functionality
- Order management
- Admin panel for managing:
  - Users
  - Products
  - Orders
- Responsive design

## Docker Commands

**Start containers:**
```bash
docker compose up -d
```

**Stop containers:**
```bash
docker compose down
```

**Rebuild containers:**
```bash
docker compose up -d --build
```

**View logs:**
```bash
docker compose logs -f
```

**Reset database (warning: deletes all data):**
```bash
docker compose down -v
docker compose up -d --build
```

## Structure

```
FPGS-DAW-DWES-SIMPLE/
├── app/
│   ├── controllers/       # MVC Controllers
│   ├── data/             # Repositories and DbContext
│   ├── models/           # Data models
│   ├── services/         # Business logic services
│   ├── utils/            # Utility classes
│   └── views/            # HTML/PHP views
├── public/
│   ├── css/              # Stylesheets
│   ├── images/           # Static images
│   └── index.php         # Application entry point
├── apache.Dockerfile      # Apache + PHP 8.4 container
├── mysql.Dockerfile       # MySQL 8.0 container
├── docker-compose.yml     # Docker orchestration
├── db_init.sql           # Database schema
├── db_seed.sql           # Sample data
└── readme.md
```

## Author

**Sergio** - [adesduende](https://github.com/adesduende)

## License

This project is for educational purposes.
