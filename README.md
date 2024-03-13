# Custom PHP Framework  

## Description
This custom PHP framework is designed to streamline the development of web applications by providing a robust, flexible architecture.
It incorporates modern development principles and design patterns to facilitate rapid development, easy maintenance, and high scalability.  
### BIG NOTE:  
This a custom framework currently in development and should not be used in production environments.
Its primary goal is to show the general structure and features of a PHP framework. Most of the code is pseudo-code and not fully functional.

## Features
1. MVC Architecture: Implements the Model-View-Controller (MVC) pattern to separate logic, UI, and database interaction, making your application organized and scalable.
2. Custom Routing: A flexible routing system that allows for clean URLs and easy mapping of URLs to controller actions.
3. Database Abstraction: Features an abstraction layer for database operations, supporting multiple database types and simplifying CRUD operations.
4. Command Line Interface: Includes console.php for running scheduled tasks, migrations, and other backend processes.
5. Configuration Management: Centralized configuration management in the config directory, allowing for easy environment-based settings adjustments.
6. CSFR Protection: Provides built-in protection against Cross-Site Request Forgery (CSRF) attacks.
7. Error Handling: Custom error handling and logging to help identify and resolve issues quickly.
8. IoC Container: A simple IoC container for managing class dependencies and facilitating dependency injection.
9. Templating Engine: A simple templating engine for rendering views and passing data to the UI.
10. Session Flash Messages: Supports session-based flash messages for displaying notifications to users.
11. Middleware: Middleware support for filtering and modifying HTTP requests and responses.
12. Query Builder: A simple query builder for building SQL queries in a programmatic way.
13. Migration System: A migration system for managing database schema changes and version control.
14. TODO add redis cache

## Installation
### Clone the Repository
```bash
git clone https://github.com/AlexVanchov/custom-framework
cd custom-framework
```
### Install Dependencies
```bash
composer install
```
### Web Server Configuration
Configure your web server (e.g., Apache, Nginx) to point the document root to the web directory.
Ensure mod_rewrite is enabled for Apache to support clean URLs.
### Environment Configuration
Config - config/config.php.  
Adjust database settings, application keys, and other environment-specific configurations in config/config.php.
Create db.php in /config directory and add your database credentials.  
Example db.php:  
```php
return [
	'db' => [
		'dsn' => 'mysql:host=localhost;dbname=mydatabase;charset=utf8',
		'user' => 'myuser',
		'password' => 'mypassword',
	]
];
```

## Usage:
### Directory Structure
- app/: Contains the MVC components:
  - Controllers/: Application logic controllers.

  - Models/: Data models for database interaction.

  - Views/: Template files for rendering the UI.

- config/: Configuration files, including database settings, routes, and application-specific configurations.

- core/: The framework's core functionalities and base classes.

- web/: Publicly accessible directory containing the front controller (index.php) and assets (CSS, JavaScript, images).

### Defining Routes

Define your application's routes in /app/config/routes.php.
The router connects URLs with controller actions.
```php
['GET', '', 'HomeController' . '@index'],
['POST', '', 'HomeController' . '@index'],
```

### Creating Controllers
Controllers handle incoming requests and return responses. Place your controllers in /app/Controllers.

Example: HomeController.php
```php
namespace App\Controllers;

use Core\BaseController;
use Core\Http\Response;

class HomeController extends BaseController {
    public function index() {
        return $this->view->render('home/index', [
            'title' => 'Welcome to my Framework'
        ]);
    }
}
```

### Models and Database Access
Instructions on creating models and accessing the database.
Models represent and interact with data. Place your models in /app/Models.

Example: User.php
```php
namespace App\Models;

use Core\Database\BaseModel;

class User extends BaseModel {
    protected $table = 'users';
    
    // Model methods...
}
```

### Views and Templating
Views contain the HTML of your application and display data. Place your views in /app/Views.

Creating a View:

Create a file /app/Views/home/index.php for the HomeController@index action
```php
<html>
<head>
    <title><?= $title; ?></title>
</head>
<body>
    <h1><?= $title; ?></h1>
    <p>Welcome</p>
</body>
</html>
```