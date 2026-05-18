# Nokia DMS

A complete Distribution Management System (DMS) and e-Warranty activation platform for Nokia Mobile in Bangladesh. Built on Laravel 5.7, this ERP system handles distributor and retailer management, inventory tracking, sales order processing, and electronic warranty registration for Nokia Mobile devices.

## Requirements

- PHP ^7.1.3
- MySQL 5.7+
- Composer
- Node.js & NPM (for frontend assets)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url> nokia-dms
   cd nokia-dms
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` and set your database credentials and app URL:
   ```
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Database migration & seeding**
   ```bash
   php artisan migrate --seed
   ```

6. **Install & compile frontend assets**
   ```bash
   npm install
   npm run production
   ```

7. **Set storage permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.
