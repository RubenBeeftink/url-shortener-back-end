# ShortUrlBackend
This application aims to allow users to create shortened urls. The shortened urls then redirect to the origin url.

This project provides a back-end api integration for the front-end part of this application (https://github.com/RubenBeeftink/url-shortener-front-end).

This project is built upon the Domain Driven Design principle. It includes simple and understandable actions to provide functionality and very readable code. This is also the reason the project has minimal PhpDoc, as I believe good code should describe itself.
It follows the basic Model, View, Controller principe as most modern frameworks do.

The project uses Laravel, as well as Laravel Sanctum (api token management) and Laravel Foritify (authentication management) for scaffolding.

The entry point for this application is the `routes/api.php` file. It includes all routes needed for CRUD actions on a short-url, as well as user registration and login routes. From there routes are passed onto the relevant controllers and the controllers execute the nessecary actions.

This application provides the following:
- short url CRUD actions (create/read/update/delete)
- user authentication (register/login)
- a web route that redirects the shortened url back to the original url

## basic principles
This application creates a random hash every time a new short url entry is created. This hash is then stored in the database and creates a full short url. The created short url resolves to this application. The application then checks if the given hash matches, and if it does, redirects the user to the original url.
If the short url is expired or not found the application will return a 404 not found response.

## Test coverage
All files added by myself (and not framework scaffolding, such as laravel Sanctum and Laravel fortify) are covered by integration and feature tests.
![image](https://github.com/user-attachments/assets/a82327cd-d867-4ea5-b1aa-f60f6952d343)

To run the test suite run `php artisan test`. Make sure you create a .env.testing file and fill the `APP_KEY` variable with the value from your .env file.

## Running the project
You can run this project using the built-in Laravel webserver. To do so:

- Create a mysql database named `shortener` on your local machine
- run `composer install` to install composer dependencies
- run `php artisan key:generate` to generate an app key
- run `php artisan migrate` to migrate the database
- run `php artisan serve` to host the application. It runs on `localhost:8000`
