# Food King - Restaurant Food Ordering & Delivery App

[![Laravel](https://img.shields.io/badge/Laravel-9.19-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.2.37-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://mysql.com)
[![MIT License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A comprehensive restaurant food ordering and delivery application built with Laravel 9 and Vue.js 3, featuring a modern admin panel and customer-facing interface.

## 🚀 Features

### Customer Features
- **Browse Menu**: Explore categories and items with images
- **User Authentication**: Register and login functionality
- **Shopping Cart**: Add, update, and remove items
- **Order Tracking**: Real-time order status updates
- **Payment Integration**: Multiple payment gateways
- **Location Services**: Address management and delivery
- **Reviews & Ratings**: Rate orders and items
- **Promotions**: Coupons and discount codes

### Admin Features
- **Dashboard**: Comprehensive analytics and insights
- **Menu Management**: Categories, items, variations, and addons
- **Order Management**: Process and track orders
- **User Management**: Customer and staff management
- **Reports**: Sales, orders, and performance analytics
- **Settings**: Configure app settings and preferences
- **Notifications**: Push notifications and email alerts

### Technical Features
- **Responsive Design**: Mobile-first approach
- **Real-time Updates**: WebSocket integration
- **Multi-language Support**: Internationalization ready
- **API-Driven**: RESTful API architecture
- **Role-Based Access**: Different user permissions
- **File Upload**: Image management with Laravel Media Library

## 🛠️ Tech Stack

- **Backend**: Laravel 9.x Framework
- **Frontend**: Vue.js 3.x with Composition API
- **Database**: MySQL 5.7+
- **Styling**: Tailwind CSS + Custom CSS
- **Build Tool**: Laravel Mix (Webpack)
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage with symbolic links
- **Real-time**: Laravel Broadcasting (optional)
- **Payment**: Multiple gateway integrations

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP**: 8.0 or higher
- **Composer**: 2.x
- **Node.js**: 16.x or higher (with npm)
- **MySQL**: 5.7 or higher
- **Git**: Latest version

## 🚀 Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/stackmasteraliza/food_king.git
cd food_king
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node.js Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
cp .env.example .env
```

Update the `.env` file with your configuration:
```env
APP_NAME="Food King"
APP_ENV=local
APP_KEY=base64:your_app_key_here
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=food_king
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Generate application key
php artisan key:generate
```

### 5. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE food_king;
EXIT;

# Run migrations
php artisan migrate

# Seed the database (optional - includes demo data)
php artisan db:seed
```

### 6. Storage Setup
```bash
# Create symbolic link for file storage
php artisan storage:link
```

### 7. Build Frontend Assets
```bash
# For development
npm run dev

# For production
npm run prod
```

### 8. Start the Application
```bash
# Start Laravel development server
php artisan serve

# The application will be available at: http://localhost:8000
```

## 🔧 Configuration

### Payment Gateways
Configure payment gateways in your `.env` file:

```env
# Stripe
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key

# PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret

# And more...
```

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@foodking.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Push Notifications (FCM)
```env
FCM_SECRET_KEY=your_firebase_server_key
FCM_TOPIC=food_king_orders
```

## 📱 Usage

### Admin Panel
- **URL**: `http://localhost:8000/admin`
- **Default Admin Credentials**:
  - Email: admin@foodking.com
  - Password: password

### Customer Interface
- **URL**: `http://localhost:8000`
- Register/Login to place orders

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run frontend tests (if configured)
npm test
```

## 🚀 Deployment

### Production Build
```bash
# Build optimized assets
npm run prod

# Cache configuration for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Web Server Configuration
Configure your web server (Apache/Nginx) to point to the `public` directory.

### SSL Certificate
Ensure HTTPS is configured for production environments.

## 📁 Project Structure

```
food_king/
├── app/                    # Laravel application code
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   ├── Models/
│   └── Providers/
├── bootstrap/              # Laravel bootstrap
├── config/                 # Configuration files
├── database/               # Migrations and seeders
│   ├── migrations/
│   └── seeders/
├── public/                 # Public assets
├── resources/              # Views and frontend assets
│   ├── js/                # Vue.js components
│   ├── sass/              # Stylesheets
│   └── views/             # Blade templates
├── routes/                 # Route definitions
├── storage/                # File storage
├── tests/                  # Test files
├── .env.example           # Environment template
├── artisan                # Laravel CLI
├── composer.json          # PHP dependencies
├── package.json           # Node dependencies
└── webpack.mix.js         # Asset compilation
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel Framework
- Vue.js Team
- Tailwind CSS
- All contributors and supporters

## 📞 Support

For support, email support@foodking.com or join our Discord community.

## 🔄 Updates

Stay updated with the latest features and improvements by following our [changelog](CHANGELOG.md).

---

**Made with ❤️ for food lovers worldwide** 🍕🍔🍟