This project is a Task Mangment built with Laravel 10 that provides a RESTful APIf for managing Tasks ,It allows Admin & manager to perform CRUD operations (Create, Read, Update, Delete) on tasks with the ability to filter tasks by all attribute,or show the tasks that delete , and get the daily report for the task update. 


Key Features:

CRUD operation on task: (create,update,delete,show)the admin & manager can do this operation but the manager can update & delete onle on the task that create it.
Filtering : filter the task by status & priority  and other attribute by use scop query.
use services for clean separation of concerns.
Form Requests: Validation is handled by custom form request classes.
event & listener: for dinamic update to due_date & status
Use Job Queues to improve system performance when dealing with a large number of tasks or users. For example, daily performance reports can be scheduled in the background
use Caching to store frequently searched tasks to speed up responses.
Use advanced protection technologies to avoid famous attacks
API Response Service: Unified responses for API endpoints.

Resources: API responses are formatted using Laravel resources for a consistent structure.

### Technologies Used:
- **Laravel 10**
- **PHP**
- **MySQL**
- **XAMPP** (for local development environment)
- **Composer** (PHP dependency manager)
- **Postman Collection**: Contains all API requests for easy testing and interaction with the API.


## Installation

### Prerequisites

Ensure you have the following installed on your machine:
- **XAMPP**: For running MySQL and Apache servers locally.
- **Composer**: For PHP dependency management.
- **PHP**: Required for running Laravel.
- **MySQL**: Database for the project
- **Postman**: Required for testing the requestes.

### Steps to Run the Project

1. Clone the Repository  
   ```bash
   git clone https://github.com/KhatoonBadrea/task_managment_system_with_security
2. Navigate to the Project Directory
   ```bash
   cd tasks-library
3. Install Dependencies
   ```bash
   composer install
4. Create Environment File
   ```bash
   cp .env.example .env
   Update the .env file with your database configuration (MySQL credentials, database name, etc.).
5. Generate Application Key
    ```bash
    php artisan key:generate
6. Run Migrations
    ```bash
    php artisan migrate
7. Seed the Database
    ```bash
    php artisan db:seed
8. Run the job
    php artisan queue:work
9. Run the Application
    ```bash
    php artisan serve
10. Interact with the API and test the various endpoints via Postman collection 
    Get the collection from here:https://documenter.getpostman.com/view/37831879/2sAXxV7WPQ