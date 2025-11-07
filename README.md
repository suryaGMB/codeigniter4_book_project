# 🚀 Project Setup Instructions (For This Application)

### 1. Create Database in XAMPP (phpMyAdmin)

Go to:
http://localhost/phpmyadmin

Create a database named: ci4_bookstore

### 2. Install Dependencies

```bash
composer install
```

### 3. . Copy Environment File

cp env .env

### 4. Configure .env Database Settings

database.default.hostname = localhost
database.default.database = ci4_bookstore
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

### 5. Run Migrations (Create Tables)

php spark migrate

### 6. Seed Admin User

php spark db:seed AdminSeeder


### Admin Login Credentials:

Email: admin@example.com
Password: admin123


### 7. Start Development Server

php spark serve



