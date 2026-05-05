# TasteTown Restaurant Management System

TasteTown is a beginner-friendly PHP and MySQL restaurant ordering project built for XAMPP. It includes customer registration and login, menu browsing, search and filtering, order placement, order history, and an admin area for managing food items and viewing orders.

## Project Overview

This project is a simple restaurant web application where:

- Customers can create an account, log in, browse food items, place orders, and review their order history.
- Admin users can log in to a dashboard, add food items with images, delete food items, and view all customer orders.

The UI is built with plain HTML, CSS, and JavaScript, while the backend uses PHP sessions and MySQL for authentication and data storage.

## Tech Stack

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- XAMPP / Apache

## Main Features

- User registration with hashed passwords
- User login and logout
- Role-based access control for admin and normal users
- Homepage with featured food items
- Menu page with search and veg/non-veg filtering
- Dedicated search page
- Order placement flow
- Customer order history
- Admin dashboard with summary counts
- Add food items with image upload
- Delete food items and uploaded images
- View all orders from admin panel

## Project Structure

```text
Restaurant/
├── about.php
├── add_food.php
├── admin_dashboard.php
├── config/
│   └── db.php
├── contact.php
├── css/
│   └── style.css
├── database.sql
├── db.php
├── delete_food.php
├── history.php
├── images/
├── includes/
│   ├── footer.php
│   └── navbar.php
├── index.php
├── js/
│   └── script.js
├── login.php
├── logout.php
├── menu.php
├── order.php
├── register.php
├── search.php
├── view_orders.php
└── README.md
```

## User Roles

### Customer

- Register a new account
- Log in and log out
- Browse menu items
- Search for dishes
- Place orders
- View personal order history

### Admin

- Access `admin_dashboard.php`
- Add food items with image uploads
- Delete food items
- View all customer orders

## Important Pages

- `index.php` - homepage with hero section and featured food
- `menu.php` - full menu listing with filters
- `search.php` - keyword-based search page
- `register.php` - user registration
- `login.php` - authentication page
- `order.php` - place an order
- `history.php` - logged-in user's order history
- `admin_dashboard.php` - admin overview
- `add_food.php` - admin food creation form
- `delete_food.php` - admin food deletion page
- `view_orders.php` - admin order list

## Prerequisites

Before running the project, make sure you have:

- XAMPP installed
- Apache server running
- MySQL server running
- PHP 8.x recommended
- A browser for testing the application

## Installation and Setup

### 1. Place the project in XAMPP `htdocs`

This project should be inside:

```text
/Applications/XAMPP/xamppfiles/htdocs/Restaurant
```

### 2. Start Apache and MySQL

Open XAMPP Control Panel and start:

- Apache
- MySQL

### 3. Create the database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Then import the provided `database.sql` file into MySQL.

Recommended database name:

```text
restaurant_db
```

### 4. Verify database connection

The app uses [config/db.php](/Applications/XAMPP/xamppfiles/htdocs/Restaurant/config/db.php:1) for database access.

Current default configuration:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant_db";
```

Update these values only if your local MySQL setup is different.

### 5. Open the project in the browser

```text
http://localhost/Restaurant
```

## Database Notes

The project includes two database-related files:

- [database.sql](/Applications/XAMPP/xamppfiles/htdocs/Restaurant/database.sql:1) - schema and default admin insert
- [config/db.php](/Applications/XAMPP/xamppfiles/htdocs/Restaurant/config/db.php:1) - runtime connection file that also attempts automatic database/table creation

### Recommended setup path

Use `database.sql` as the primary setup source, because it matches the application pages more closely.

### Current schema mismatch to know about

There is an inconsistency in the current codebase:

- `database.sql` creates `food_type` in the `food` table and `order_date` in the `orders` table.
- `config/db.php` currently creates `food` without `food_type`.
- `config/db.php` currently creates `orders` with `created_at` and `order_status` instead of `order_date`.

Because pages such as `menu.php`, `history.php`, and `view_orders.php` expect `food_type` and `order_date`, importing `database.sql` is the safer option for running the project.

If the database was auto-created only through `config/db.php`, some pages may fail until the schema is adjusted.

## Default Admin Login

The SQL file inserts a default admin account:

- Email: `admin@tastetown.com`
- Password: `admin123`

Note: [config/db.php](/Applications/XAMPP/xamppfiles/htdocs/Restaurant/config/db.php:1) uses a different default admin email (`admin@restaurant.com`) when it auto-creates data. This is another reason to prefer importing `database.sql` and sticking to one setup method.

## Image Uploads

Admin food images are uploaded into the `images/` folder.

Make sure:

- The `images/` directory exists
- Apache/PHP has permission to write to it

Supported image types in the current code:

- JPG / JPEG
- PNG
- WEBP

## Authentication and Sessions

The application uses PHP sessions to manage login state and role access.

- Logged-in users get `user_id` stored in session
- Admin users are identified by `role = admin`
- Protected pages redirect unauthenticated users to `login.php`

## Known Issues in the Current Codebase

These are useful to know before extending the project:

- `config/db.php` schema does not fully match the SQL file or the pages using the database.
- `index.php` checks `$_SESSION['user_name']`, while login currently stores `$_SESSION['name']`.
- `search.php` reads the query from `q`, but the homepage search form sends `search`, so homepage search does not line up with that page yet.
- `includes/navbar.php` contains JavaScript for a `darkModeToggle` element that does not appear in the current markup.
- There are duplicate-looking legacy files such as root-level `style.css`, `script.js`, `navbar.php`, and `footer.php`, while the active pages mostly use `css/style.css`, `js/script.js`, and `includes/`.

## How to Use the App

### As a customer

1. Open the homepage.
2. Register a new account.
3. Log in.
4. Browse the menu or search for food.
5. Open the order page and place an order.
6. Check `My Orders` to view order history.

### As an admin

1. Log in with the admin account.
2. Open the admin dashboard.
3. Add food items with image, type, price, and description.
4. Delete food items if needed.
5. Review all customer orders from the orders page.

## Security and Learning Notes

This project is good for learning, but before production use you should improve:

- Form validation
- Error handling
- CSRF protection
- File upload hardening
- Input sanitization consistency
- Database migration/versioning
- Session security settings

## Future Improvements

- Edit/update food items
- Order status updates
- Better search from homepage
- Payment integration
- Admin user management
- Responsive UI cleanup
- Unified database migration script
- Remove duplicate legacy files



s