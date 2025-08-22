# Frontier Wishlist API

A **Laravel 12 E-Commerce Wishlist API** built with **SOLID principles**, **Dockerized infrastructure**, and **comprehensive testing**.  
This project demonstrates **authentication, product management, and wishlist functionality** — with versioned APIs and live Swagger documentation.

---

## Features

### **Authentication**
- Register, Login
- Token-based authentication (custom `UserToken` model — no Sanctum/Passport, implemented only to demonstrate the feature)

### **Products**
- List products  
- Add new products

### **Wishlist**
- Add product to wishlist  
- List wishlist items  
- Remove product from wishlist

### **API Versioning**
- Current: **v1.1**  
- Future support: **v2.0**

### **Documentation**
- Swagger UI available at: **`/docs`** (OpenAPI annotations included)

### **Testing**
- Feature tests (Pest)  
- Unit tests for services/repositories  

### **Quality Assurance**
- **PHPStan + Larastan** (static analysis)  
- **PHP-CS-Fixer** (coding standards)

---

## Installation (Docker)

Clone the repository and build the containers:

```bash
git clone git@github.com:chezaresarmiento/frontier.git
cd frontier



# Go to the correct folder to start
cd docker
create the .env file and paste the content provided by the author under .env_docker

cd ../src
Create the .env file and paste the content provided by the author under .env_laravel

# Docker ps
Check that no other service are runing, otherwise port conflicts will arise

# Start services
cd ../docker
docker compose up -d --build

#Run Composer to install dependencies
docker compose exec php composer install

# Run migrations & seeders
docker compose exec php php artisan migrate --seed

# Open Swagger
http://localhost:9000/api/documentation#/

# Try the endpoints provided
Register a user
Login with those credentials
Copy the token provided and paste it into Authorize button located uper right


```

### **Default Services**

-   PHP: `http://localhost:9000`
    
-   MySQL: `localhost:3306`
    
-   Redis: `localhost:6379` (Not using but demonstrate the tool)
    
-   Swagger UI: `http://localhost:9000/docs` (please test the endpoints here)
-   After registering a user, login and get the token
-   use it in Authorize section to test the rest of endpoints
    

----------

## Authentication

### **Register**

**Endpoint:**  
`POST /api/v1.1/register`

**Payload:**

`{  "name":  "John Doe",  "email":  "john@example.com",  "password":  "secret123"  }` 

----------

### **Login**

**Endpoint:**  
`POST /api/v1.1/login`

**Payload:**

`{  "email":  "john@example.com",  "password":  "secret123"  }` 

**Response:**

`{  "token":  "abcdef123...",  "expires_at":  "2025-08-22T10:00:00Z"  }` 

**Use in headers:**

`Authorization: Bearer <token>` 

----------

## API Endpoints (v1.1)

### **Auth**

-   `POST /api/v1.1/register` – Register new user
    
-   `POST /api/v1.1/login` – Login and get token
    
    

### **Products**

-   `GET /api/v1.1/products` – List all products
    
-   `POST /api/v1.1/products` – Create new product (admin only)
    

### **Wishlist**

-   `GET /api/v1.1/wishlist` – Get user’s wishlist
    
-   `POST /api/v1.1/wishlist` – Add product to wishlist
    
-   `DELETE /api/v1.1/wishlist/{productId}` – Remove product from wishlist
    

----------

## Running Tests

Run all tests:

`docker-compose exec php ./vendor/bin/pest` 

Run static analysis:

`docker-compose exec php ./vendor/bin/phpstan analyse --memory-limit=1G` 

Run coding style fixer:

`docker-compose exec php ./vendor/bin/php-cs-fixer fix --dry-run --diff` 

----------

##  Tech Stack

-   **Backend:** Laravel 12
    
-   **Database:** MySQL 8
    
-   **Cache/Queue:** Redis
    
-   **Containers:** Docker + Docker Compose
    
-   **Docs:** Swagger / OpenAPI
    
-   **Tests:** PestPHP, PHPUnit
    
-   **QA:** PHPStan, Larastan, PHP-CS-Fixer


**Cesar Sarmiento**  
Senior Backend Engineer | PHP • Laravel • Node.js • Redis • Elasticsearch  
📧 [cesar@example.com](mailto:cesar@opulence.com)  
🔗 [LinkedIn](https://linkedin.com/in/cesarsarmiento)