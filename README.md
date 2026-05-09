# CharityHub

A transparent online fundraising platform built with Laravel 11 for managing campaigns, donations, volunteers, and impact reports.

## Features
- Campaign management with progress tracking
- Stripe & PayMob payment gateways
- Donor certificate PDF generation with QR verification
- Volunteer registration & shift scheduling
- Impact reports with Google Maps integration
- Real-time progress bars via Livewire
- Filament v3 admin panel (admin-only access)
- Role-based access: admin / donor / volunteer

## Tech Stack
- Laravel 11, PHP 8.2
- Filament v3 (admin panel)
- Livewire v3 (real-time components)
- MySQL (via XAMPP/phpMyAdmin)
- Stripe PHP SDK
- DomPDF (certificates)
- TailwindCSS

## Setup Instructions

### 1. Clone & install
git clone <repo-url>
cd CharityHub
composer install
npm install

### 2. Environment
cp .env.example .env
php artisan key:generate

### 3. Configure .env
# Set DB credentials, Stripe keys, mail settings

### 4. Database
php artisan migrate:fresh
php artisan db:seed

### 5. Storage
php artisan storage:link

### 6. Build assets
npm run build

### 7. Create admin user
php artisan make:filament-user
# or use seeded admin: admin@charityhub.com / password

### 8. Run
php artisan serve

## URLs
| URL | Description |
|-----|-------------|
| / | Redirects to campaigns |
| /campaigns | Public campaign listing |
| /campaigns/{slug} | Campaign detail + donation form |
| /admin | Filament admin panel |
| /register | User registration |
| /login | User login |
| /certificates/verify/{token} | Certificate verification |

## Test Credentials
- Admin: admin@charityhub.com / password
- Donor: ahmed@example.com / password
- Volunteer: mariam@example.com / password

## Running Tests
php artisan test

## Queue Worker (for emails & certificates)
php artisan queue:work --tries=3
# Or use QUEUE_CONNECTION=sync for local dev