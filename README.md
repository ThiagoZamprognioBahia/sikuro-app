<p align="center"><a href="https://sikurogroup.com/" target="_blank"><img src="https://sikurogroup.com/wp-content/uploads/2024/03/sikuro-bianco.png" width="400" alt="Laravel Logo"></a></p>

# 🏢 Sikuro API

RESTful API developed with Laravel for managing companies and employees, with support for authentication, soft deletes, file uploads, and automated testing.

---

## ⚙️ Technologies

- PHP 8.4  
- Laravel 12  
- MariaDB  
- Sanctum  
- PHPUnit  
- Laravel Factory & Seeders  
- Eloquent ORM  
- SoftDeletes  
- Repository Pattern  

---

## 📁 Project Structure

- `App\Http\Controllers\Api` — API Controllers  
- `App\Repositories` — Repositories following the Repository pattern  
- `App\Models` — Eloquent Models  
- `App\Http\Requests` — Custom validations using Form Requests  
- `App\Http\Resources` — Formatted API responses (Resources)  
- `tests/Feature` — Feature tests using PHPUnit  
- `database/factories` — Fake data generation for testing  
- `storage/app/public/logos` — Uploads for company logos  

---

## 🚀 Main Endpoints

### 🧾 Companies

| Method | Route                   | Description                             |
|--------|-------------------------|-----------------------------------------|
| GET    | `/api/companies`        | List companies (paginated)              |
| GET    | `/api/companies/{id}`   | View details of a specific company      |
| POST   | `/api/companies`        | Create a new company                    |
| PUT    | `/api/companies/{id}`   | Update an existing company              |
| DELETE | `/api/companies/{id}`   | Soft delete a company                   |

> `logo_path` upload supported via `multipart/form-data`.

### 👨‍💼 Employees

| Method | Route                    | Description                             |
|--------|--------------------------|-----------------------------------------|
| GET    | `/api/employees`         | List employees (paginated)              |
| GET    | `/api/employees/{id}`    | View details of a specific employee     |
| POST   | `/api/employees`         | Create a new employee                   |
| PUT    | `/api/employees/{id}`    | Update an existing employee             |
| DELETE | `/api/employees/{id}`    | Soft delete an employee                 |

---

## 🛡️ Authentication

This API uses **Sanctum** for authentication. Add the Bearer token to the request header:

## 🧰 How to Use & Install Locally

Follow the steps below to set up and run the Sikuro API on your local machine:

### 📥 Clone the Repository
``
git clone https://github.com/ThiagoZamprognioBahia/sikuro-app
cd sikuro-app
``

## 📦 Install Dependencies
``
composer install
``

## ⚙️ Configure the Environment

Duplicate the .env.example file:
Open the .env file and configure the following section with your local database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```
# use to create the seeder/system admin user
```
ADMIN_EMAIL=admin@admin.com
ADMIN_PASSWORD=password
```

## :closed_lock_with_key: Generate the application key:
``
php artisan key:generate
``
## 🗃️ Run Migrations & Seeders
``
php artisan serve
``
# 🧪 Running Tests
``
php artisan test
``


