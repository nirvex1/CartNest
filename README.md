# E-Commerce Store

A simple e-commerce application built with Laravel 11, featuring user authentication, product management, shopping cart, and order processing.

## Features

- User registration and authentication
- Product browsing and search
- Shopping cart functionality
- Order management
- Admin panel for product and order management
- Payment processing (simplified for demo)

## Installation

1. Clone the repository
2. Run `composer install`
3. Run `npm install`
4. Copy `.env.example` to `.env` and configure your database
5. Run `php artisan key:generate`
6. Run `php artisan migrate`
7. Run `php artisan db:seed`
8. Run `npm run build`
9. Run `php artisan serve`

## Usage

- Visit the homepage to browse products
- Register or login to add items to cart
- Checkout and process payment
- Admin users can manage products and orders via /admin

## Technologies Used

- Laravel 11
- SQLite (for simplicity)
- Tailwind CSS
- Vite for asset compilation

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
