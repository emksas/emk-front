# EMK Front

Main EMK web application for managing incomes, expenses, accounting accounts, users, and financial planning. This project is built with Laravel, Blade, Jetstream, Fortify, Vite, and Tailwind CSS.

The full system is composed of the main application and three microservices:

- `emk-front`: main Laravel frontend and backend.
- `emk-reports`: Django microservice for incomes.
- `financialPlaning`: Spring Boot microservice for financial planning.
- `emk-sena-rsr-expenses-back`: Node.js microservice for expenses and email integrations.

## Requirements

### Running with Docker

- Docker
- Docker Compose
- Git

### Running without Docker

- PHP 8.2 or higher. Docker uses PHP 8.4.
- Composer
- Node.js 20 or higher
- npm
- Python 3.12 or higher
- Java 21
- Maven or the `./mvnw` wrapper included in `financialPlaning`
- PostgreSQL, or access to the PostgreSQL database configured in `.env`

## Expected Folder Structure

To run all services with this project's `docker-compose.yml`, the repositories must be placed at the same directory level:

```text
GitHub/
|-- emk-front/
|-- emk-reports/
|-- financialPlaning/
`-- emk-sena-rsr-expenses-back/
```

The `emk-front/docker-compose.yml` file uses relative paths such as `../emk-reports`, `../financialPlaning`, and `../emk-sena-rsr-expenses-back`.

## Environment Variables

Create the `.env` file in the `emk-front` root directory:

```bash
cp .env.example .env
```

Review and update at least these values:

```env
APP_NAME=EMK
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8081

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=emk
DB_USERNAME=user
DB_PASSWORD=password

SPRING_FINANCIAL_BASE_URL=http://localhost:8082
PYTHON_INCOMES_BASE_URL=http://localhost:8084/api
NODE_EXPENSES_BASE_URL=http://localhost:3000/api
```

When Laravel runs inside Docker, these internal service URLs are recommended:

```env
SPRING_FINANCIAL_BASE_URL=http://spring:8085
PYTHON_INCOMES_BASE_URL=http://django:8084/api
NODE_EXPENSES_BASE_URL=http://node:3000/api
```

Note: the Laravel code reads `SPRING_FINANCIAL_BASE_URL`, `PYTHON_INCOMES_BASE_URL`, and `NODE_EXPENSES_BASE_URL` from `config/services.php`.

## Run Everything with Docker

Go to the Laravel project folder:

```bash
cd /path/to/GitHub/emk-front
```

Create the `.env` file if it does not exist yet:

```bash
cp .env.example .env
```

Build and start the containers:

```bash
docker compose up -d --build
```

Install PHP dependencies inside the Laravel container:

```bash
docker compose exec laravel composer install
```

Generate the Laravel application key:

```bash
docker compose exec laravel php artisan key:generate
```

Run database migrations:

```bash
docker compose exec laravel php artisan migrate
```

Optionally, run the seeders:

```bash
docker compose exec laravel php artisan db:seed
```

Clear and rebuild Laravel's cached configuration:

```bash
docker compose exec laravel php artisan optimize:clear
```

Install frontend dependencies and build assets:

```bash
npm install
npm run build
```

Open the application:

```text
http://localhost:8081
```

### Development Mode with Vite

To work with frontend hot reload:

```bash
npm run dev
```

Keep the containers running as well:

```bash
docker compose up -d
```

### Docker Logs

All services:

```bash
docker compose logs -f
```

Only Laravel:

```bash
docker compose logs -f laravel
```

Only Nginx:

```bash
docker compose logs -f nginx
```

Only Django:

```bash
docker compose logs -f django
```

Only Spring:

```bash
docker compose logs -f spring
```

Only Node:

```bash
docker compose logs -f node
```

### Stop Docker

```bash
docker compose down
```

To stop the containers and remove volumes created by Compose:

```bash
docker compose down -v
```

## Run Without Docker

To run everything without Docker, open one terminal per service.

### 1. Laravel

From `emk-front`:

```bash
cd /path/to/GitHub/emk-front
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan optimize:clear
```

Start Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

In another terminal, start Vite:

```bash
cd /path/to/GitHub/emk-front
npm run dev
```

The application will be available at:

```text
http://localhost:8000
```

### 2. Django: Incomes

From `emk-reports`:

```bash
cd /path/to/GitHub/emk-reports
cp .env.example .env
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt
python manage.py check
python manage.py runserver 0.0.0.0:8084
```

Main endpoint:

```text
http://localhost:8084/api/incomes/
```

On Windows, activate the virtual environment with:

```bash
venv\Scripts\activate
```

### 3. Spring Boot: Financial Planning

From `financialPlaning`:

```bash
cd /path/to/GitHub/financialPlaning
./mvnw clean install
./mvnw spring-boot:run
```

By default, `application.properties` defines:

```text
server.port=8082
```

Local base endpoint:

```text
http://localhost:8082
```

To run it on the same port used by Docker Compose:

```bash
SERVER_PORT=8085 ./mvnw spring-boot:run
```

### 4. Node.js: Expenses and Email Integrations

From `emk-sena-rsr-expenses-back`:

```bash
cd /path/to/GitHub/emk-sena-rsr-expenses-back
npm install
npm run dev
```

The service will be available at:

```text
http://localhost:3000
```

Swagger will be available at:

```text
http://localhost:3000/api-docs
```

## Used Ports

| Service | Docker | Without Docker |
| --- | --- | --- |
| Laravel/Nginx | `http://localhost:8081` | `http://localhost:8000` |
| Vite | according to `npm run dev` output | according to `npm run dev` output |
| Django incomes | `http://localhost:8084` | `http://localhost:8084` |
| Spring financial planning | `http://localhost:8085` | `http://localhost:8082` |
| Node expenses | `http://localhost:3000` | `http://localhost:3000` |

## Useful Laravel Commands

Run tests:

```bash
php artisan test
```

Run tests inside Docker:

```bash
docker compose exec laravel php artisan test
```

Clear caches:

```bash
php artisan optimize:clear
```

Recreate the database and run seeders:

```bash
php artisan migrate:fresh --seed
```

Enter the Laravel container:

```bash
docker compose exec laravel bash
```

Open Tinker:

```bash
php artisan tinker
```

## Quick Verification

With Docker:

```bash
docker compose ps
curl http://localhost:8081
curl http://localhost:8084/api/incomes/
curl http://localhost:3000
```

Without Docker:

```bash
curl http://localhost:8000
curl http://localhost:8084/api/incomes/
curl http://localhost:8082
curl http://localhost:3000
```

## Important Notes

- `docker-compose.yml` does not create a local PostgreSQL database. You must use an existing database and configure its credentials in `.env`.
- The Django microservice uses the same PostgreSQL database, and its income model points to the existing `ingreso` table.
- If Laravel environment variables change, run `php artisan optimize:clear`.
- If Laravel runs inside Docker, use internal service names such as `spring`, `django`, and `node` in the microservice URLs.
- If Laravel runs without Docker, use `localhost` in the microservice URLs.
- The Node microservice currently has a `dev` script in `package.json`. If Docker tries to run `npm start` and fails, add a `start` script to the Node microservice `package.json` or change the service command to `npm run dev`.
- The Nginx proxy for Node must point to the port where Node actually listens. The current Node code listens on `3000`.
- `app/services/ExpensesService.php` currently references `config('services.nose_expenses.base_url')`; if the expenses service does not connect, compare that name with `node_expenses` in `config/services.php`.
- `app/Http/Controllers/AccountingAccount/AccountingAccountController.php` has a direct call to `http://localhost:8082/conexion-laravel`; if the project runs with Docker, consider moving it to an environment variable or using the internal `spring` host.

## Authors

EMK S.A.S.

Development team:

- Ramses Solano
- Nicolas Rojas
- Carlos Aguilera
