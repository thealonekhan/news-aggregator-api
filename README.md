# News Aggregator API (Laravel 11)

This is a **Laravel 11** backend application that aggregates news articles from **NewsAPI, The Guardian, and New York Times**, stores them in a database, and provides RESTful APIs for a frontend to retrieve articles.

---

## Features
>Fetch articles from external APIs (**NewsAPI, The Guardian, NYT**)  
>Store news articles in **MySQL**  
>Filter articles by **search, category, source, author, date**  
>Uses **Repository & Service Pattern** for clean architecture  
>Uses **API Resources & Form Requests** for validation & structured responses  
>**API Documentation** with **Scribe**  
>Automatic news updates via **Laravel Scheduler**  

---

## Installation Guide

### **1️ Clone the Repository**
```sh
git https://github.com/thealonekhan/news-aggregator-api.git
cd news-aggregator-api
```
### **2 Install Dependencies**
```sh
composer install
```
### **3 Configure .env**
```sh
cp .env.example .env
```
•	Set up database credentials in .env
•	Add API keys:
```sh
NEWSAPI_URL=https://newsapi.org/v2/top-headlines
NEWSAPI_KEY=your_newsapi_key

GUARDIAN_URL=https://content.guardianapis.com/search
GUARDIAN_KEY=your_guardian_api_key

NYT_URL=https://api.nytimes.com/svc/topstories/v2/home.json
NYT_KEY=your_nyt_api_key
```
### **4 Run Migrations**
```sh
php artisan migrate
```
## **5. API Documentation (Postman)**  

### ** View API Documentation (Online)**
The full API documentation is available at:  
🔗 **[Postman API Docs](https://documenter.getpostman.com/view/21436431/2sAYdcssia#ef3458bf-ee3e-4cd5-95a3-6f8abe9c399d)**  

### ** Import API Collection in Postman**  
You can import the Postman Collection manually:  
1. Download the collection from this repo:  
   **[`/apiDocs/News_Aggregator_API.postman_collection.json`](./apiDocs/News_Aggregator_API.postman_collection.json)**
2. Open **Postman → Import**  
3. Select the downloaded JSON file  
4. Start testing the APIs!

---
### **6 Start Laravel Server**
```sh
php artisan serve
```

## API Usage

## Download & Store News Manually
```sh
curl -X GET http://127.0.0.1:8000/api/sync-articles
```

## Retrieve Articles (POST)
```sh
curl -X POST http://127.0.0.1:8000/api/articles \
     -H "Content-Type: application/json" \
     -d '{
          "search": "tech",
          "category": "Business"
         }'
```

## Example API Response
```sh
{
    "data": [
        {
            "id": 1,
            "title": "Tech Innovations in 2024",
            "content": "Exciting developments in AI...",
            "source": "NewsAPI",
            "author": "John Doe",
            "url": "https://newsapi.com/article1",
            "category": "Business",
            "published_at": "2024-02-20 10:00:00"
        }
    ],
    "pagination": {
        "total": 1,
        "per_page": 10,
        "current_page": 1,
        "last_page": 1,
        "next_page_url": null,
        "prev_page_url": null
    }
}
```

## Automatic News Updates (Laravel Scheduler)
The application will fetch new articles every minute using Laravel’s scheduler.

```
### **1 Set Up the System Cron Job**
To ensure Laravel’s scheduler runs, add this cron job:
```sh
crontab -e
```
Then add this line at the bottom:
```sh
* * * * * php /path/to/your-project/artisan schedule:run >> /dev/null 2>&1
```

Replace **/path/to/your-project/** with the actual path to your Laravel application.
This runs **every minute**, allowing Laravel to trigger scheduled tasks.

### **2 Test the Scheduler**
Run this command to manually trigger the cron job and verify that it works:
```sh
php artisan schedule:run
```
If successful, it will fetch new articles and store them in the database.

---

## Running Unit Tests

This project includes **automated tests** using Laravel's **PHPUnit** framework.

### ** Setup for Testing**
Before running tests, ensure your `.env.testing` file is set up:

**1 Create a separate test database:**
```sh
mysql -u root -p
CREATE DATABASE news_aggregator_test;
EXIT;
```
**2 Ensure .env.testing is configured correctly:**
```sh
DB_CONNECTION=mysql
DB_DATABASE=news_aggregator_test
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

**3 Ensure .env.testing is configured correctly:**
```sh
php artisan migrate --env=testing
```
## Running Tests
To execute unit and feature tests, use the following command:
```sh
php artisan test
```
Alternatively, you can use PHPUnit directly:
```sh
vendor/bin/phpunit
```
Expected Output (If Tests Pass)
```sh
   PASS  Tests\Feature\ArticleTest
  ✓ it fetches articles successfully
  ✓ it filters articles by category

  Tests: 2 passed (2 assertions)
```

---

## Docker Setup

This project is fully **Dockerized** using **Apache, MySQL, and PHP 8.2**.  
Follow these steps to run the Laravel API inside Docker.

---

### ** Prerequisites**
Make sure you have **Docker** and **Docker Compose** installed:  
- [Download Docker](https://www.docker.com/get-started)

---

### ** Setup and Run Containers**
**1 Clone the repository (if not already done)**  
```sh
git clone https://github.com/thealonekhan/news-aggregator-api.git
cd news-aggregator-api
```
**2 Copy the .env file and configure database settings**  
```sh
cp .env.example .env
```
• Modify .env to use Docker’s MySQL container:
```sh
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=news_aggregators
DB_USERNAME=laravel
DB_PASSWORD=1234
```

**3 Build and start the Docker containers**  
```sh
docker-compose up -d --build
```
This will start:
• PHP 8.2 + Apache (laravel_app)
• MySQL 8.0 (laravel_db)

**4 Run database migrations inside the container**  
```sh
docker exec -it laravel_app bash
php artisan migrate --seed
php artisan storage:link
exit
```

**Access the Application**
• Open http://localhost:8000 in your browser to access the Laravel API.

---

**Managing Docker Containers**
• Check running containers:
```sh
docker ps
```
• Stop the containers:
```sh
docker-compose down
```
Now, your Laravel API is running inside Docker with Apache & MySQL!

## Contributing
Pull requests are welcome. Please follow PSR-4 coding standards.

## License
This project is open-source under the MIT License.