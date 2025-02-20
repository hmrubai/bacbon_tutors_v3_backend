## BacBon Tutors Backend V3

BacBon Tutors Backend Application is built with Laravel, providing robust and scalable solutions for managing home tuition services. It streamlines operations, enhances student-teacher interactions, and supports efficient data handling to ensure a seamless tutoring experience.

## Features
- JWT Authentication
- Role Based Authentication
- User Management
- Role Management
- Permission Management
- Docker Support


## Instructions

1. Clone the repository
2. Run `composer install`
3. cp .`env.example .env or copy .env.example .env.`
4. Run `php artisan key:generate`
5. Run `php artisan jwt:secret`
6. Create a database and update the `.env` file
7. if use sqlite Database touch /home/user/path/laravel-11-master/database/database.sqlite
8. Run `php artisan migrate` or `php artisan migrate:fresh --seed`
9. Run `php artisan serve`
10. Open Postman and import the collection from the `postman` directory
11. Swagger API Documentation: `http://localhost:8000/swagger/documentation`


## Contributor

- **[Hosne Mobarak Rubai](https://github.com/hmrubai/)**
- **[Eashen Mahmud](https://github.com/EashenMahmud)**

