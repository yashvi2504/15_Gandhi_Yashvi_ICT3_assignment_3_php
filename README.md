# PHP Practical Assignment – Complete Starter Project

## Requirements
- PHP 8+, MySQL, Apache (XAMPP), PHP GD extension enabled
- Node.js for the Express API exercises
- Create database by importing `database.sql` in phpMyAdmin.
- Put `shopping/` inside `htdocs/` and open `http://localhost/shopping/`.
- Update DB credentials in `shopping/config.php` if needed.

## Included
1. Shopping cart: admin login, category CRUD, product CRUD with image upload/resizing, user registration with session CAPTCHA, login/logout, category list, AJAX product pagination, add-to-cart and cart display.
2. Import `students.xml` into MySQL.
3. Export MySQL students to `students.xml`.
4. List files in a directory.
5. PHP calls an Express REST API.
6. Express calls a PHP REST API.

## Demo admin
Username: `admin`
Password: `password`
The database seed stores a password hash generated for `admin123`. For production, change this password and add CSRF protection, validation, and stronger authorization.

## Setup
1. Start Apache and MySQL in XAMPP.
2. Import `database.sql`.
3. Copy `shopping` folder into `C:\xampp\htdocs\`.
4. Ensure `shopping/uploads/` is writable and PHP GD is enabled.
5. Visit `http://localhost/shopping/`.
6. Admin: `http://localhost/shopping/admin/login.php`.

## XML exercises
Open `xml/import.php` to import `xml/students.xml`; open `xml/export.php` to create/download the XML file. These scripts use the same database credentials in `shopping/config.php`.

## API exercises
- Start Express: `cd express-api && npm install && npm start` (port 3000).
- PHP -> Express: open `http://localhost/shopping/api/express_client.php`.
- Express -> PHP: keep Apache running, then open `http://localhost:3000/php-client`.
- PHP REST API endpoint: `http://localhost/shopping/api/students.php`.

Use only test data. This is an educational starter, not production-hardened.
