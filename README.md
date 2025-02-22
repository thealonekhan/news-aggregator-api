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
git clone https://github.com/yourusername/news-aggregator.git
cd news-aggregator
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
### **5 Generate API Docs**
```sh
php artisan scribe:generate
```
Now visit:
```sh
http://127.0.0.1:8000/docs
```
### **6 Start Laravel Server**
```sh
php artisan serve
```

## API Usage

## Fetch & Store News Manually
```sh
curl -X GET http://127.0.0.1:8000/api/fetch-articles
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
The application will fetch new articles every hour using Laravel’s scheduler.

### **1 Add the Cron Job in Laravel 11**
Open routes/console.php and add this code below the existing commands:
```sh
use Illuminate\Support\Facades\Schedule;
use App\Services\NewsService;

Schedule::call(function () {
    app(NewsService::class)->fetchNews();
})->everyMinute();
```
### **2 Set Up the System Cron Job**
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

### **3 Test the Scheduler**
Run this command to manually trigger the cron job and verify that it works:
```sh
php artisan schedule:run
```
If successful, it will fetch new articles and store them in the database.


## Contributing
Pull requests are welcome. Please follow PSR-4 coding standards.

## License
This project is open-source under the MIT License.