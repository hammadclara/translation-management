# Translation Management Service

The Translation Management Service is a scalable, secure, and high-performance API-driven service built with PHP Laravel. It allows you to store, manage, and retrieve translations for multiple locales (e.g., en, fr, es) and tag them for context (e.g., mobile, desktop, web). The service also provides a JSON export endpoint to supply translations for frontend applications like Vue.js.

## Features

- **Multi-locale Support**: Store translations for multiple languages.
- **Tagging**: Tag translations for context (e.g., mobile, web).
- **CRUD Operations**: Create, update, view, and search translations.
- **JSON Export**: Export translations as JSON for frontend applications.
- **Scalable**: Designed to handle large datasets efficiently.
- **Secure**: Token-based authentication to secure the API.
- **Swagger Documentation**: Fully documented API using OpenAPI/Swagger.

## Technologies Used

- **PHP Laravel**: Backend framework.
- **MySQL**: Database for storing translations.
- **Docker**: Containerization for easy setup and deployment.
- **Swagger/OpenAPI**: API documentation.
- **Laravel Passport**: Secures API endpoints with Bearer Token authentication.

## Authentication with Laravel Passport

This API uses Laravel Passport for authentication. All API requests require a **Bearer Token** in the `Authorization` header.

### Obtaining a Bearer Token

To obtain an access token, send a `POST` request to `/oauth/token` with the following payload:

```json
{
  "grant_type": "password",
  "client_id": "your-client-id",
  "client_secret": "your-client-secret",
  "username": "user@example.com",
  "password": "your-password",
  "scope": "*"
}
```

#### Example cURL Request
```bash
curl -X POST "http://localhost:8080/oauth/token" \
     -H "Content-Type: application/json" \
     -d '{
       "grant_type": "password",
       "client_id": "your-client-id",
       "client_secret": "your-client-secret",
       "username": "user@example.com",
       "password": "your-password",
       "scope": "*"
     }'
```

The response will contain an access token:

```json
{
  "token_type": "Bearer",
  "expires_in": 31536000,
  "access_token": "your-access-token",
  "refresh_token": "your-refresh-token"
}
```

### Using the Bearer Token

Include the token in the `Authorization` header of each request:

```bash
curl -X GET "http://localhost:8080/translations" \
     -H "Authorization: Bearer your-access-token"
```

## Installation with Docker

### Prerequisites

- Docker installed on your machine.
- Docker Compose installed.

### Steps to Set Up

1. Clone the Repository:
   ```bash
   git clone https://github.com/your-username/TranslationManagementService.git
   cd TranslationManagementService
   ```
2. Set Up Environment Variables:
   ```bash
   cp .env.example .env
   ```
   Update the following variables in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=translation_db
   DB_USERNAME=root
   DB_PASSWORD=root
   ```
3. Build and Run Docker Containers:
   ```bash
   docker-compose up -d
   ```
4. Install PHP Dependencies:
   ```bash
   docker-compose exec app composer install
   ```
5. Generate Application Key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```
6. Run Migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```
7. Run Database Seeding:
   ```bash
   docker-compose exec app php artisan db:seed
   ```
8. Generate Swagger Documentation:
   ```bash
   docker-compose exec app php artisan l5-swagger:generate
   ```

## API Documentation

The API is fully documented using Swagger/OpenAPI. You can access the Swagger UI at:

```
http://localhost:8080/documentation
```

### API Endpoints

#### 1. Create a Translation

- **Method**: POST
- **URL**: `/translations`
- **Request Body**:
  ```json
  {
    "key": "welcome_message",
    "content": {
      "en": "Welcome",
      "fr": "Bienvenue"
    },
    "tag": "web"
  }
  ```
- **Response**:
  ```json
  {
    "id": 1,
    "key": "welcome_message",
    "content": {
      "en": "Welcome",
      "fr": "Bienvenue"
    },
    "tag": "web",
    "created_at": "2023-10-01T12:00:00.000000Z",
    "updated_at": "2023-10-01T12:00:00.000000Z"
  }
  ```

#### 2. Update a Translation

- **Method**: PUT
- **URL**: `/translations/{id}`
- **Request Body**:
  ```json
  {
    "content": {
      "en": "Hello",
      "fr": "Bonjour"
    },
    "tag": "mobile"
  }
  ```
- **Response**:
  ```json
  {
    "id": 1,
    "key": "welcome_message",
    "content": {
      "en": "Hello",
      "fr": "Bonjour"
    },
    "tag": "mobile",
    "created_at": "2023-10-01T12:00:00.000000Z",
    "updated_at": "2023-10-01T12:05:00.000000Z"
  }
  ```

## Testing

To run tests, execute the following command:

```bash
docker-compose exec app php artisan test
```

## License

This project is licensed under the MIT License. See the LICENSE file for details.

