# Team Project Management System

## Description


### Key Features:


### Key Principles:


### Technologies Used:
- **Laravel 10**
- **PHP**
- **MySQL**
- **XAMPP** 
- **Composer** 
- **Postman Collection**: Contains all API requests for easy testing and interaction with the API.

---

## Installation

### Prerequisites

Ensure you have the following installed on your machine:
- **XAMPP**: For running MySQL and Apache servers locally.
- **Composer**: For PHP dependency management.
- **PHP**: Required for running Laravel.
- **MySQL**: Database for the project.
- **Postman**: Required for testing the requests.

### Steps to Run the Project

1. Clone the Repository  
   ```bash
   git clone https://github.com/TukaHeba/Team_Project.git
2. Navigate to the Project Directory
   ```bash
   cd Team_Project
3. Install Dependencies
   ```bash
   composer install
4. Create Environment File
   ```bash
   cp .env.example .env   
5. Generate Application Key
    ```bash
    php artisan key:generate
6. Run Migrations
    ```bash
    php artisan migrate
7. Seed the Database
    ```bash
    php artisan db:seed
8. Run the Application
    ```bash
    php artisan serve
9. Interact with the API and test the various endpoints via Postman collection 
    Get the collection from here: 
