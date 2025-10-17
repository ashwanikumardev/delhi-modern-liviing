# 🏠 Delhi Modern Living - PG/Hostel Booking Website

A comprehensive PG/Hostel booking platform built with PHP (MVC architecture), MySQL, and modern web technologies. Features both user-facing booking system and admin management dashboard.

**Version**: 2.0.0 | **Status**: Production Ready ✅ | **Last Updated**: October 2025

## 🌟 Features

### User Features
- **Authentication System**: Secure signup/login with password reset
- **Room Browsing**: Advanced search and filtering with categories
- **Booking System**: Cart-based booking with multiple payment options
- **User Dashboard**: Profile management and booking history
- **Responsive Design**: Mobile-first design with TailwindCSS

### Admin Features
- **Dashboard**: Comprehensive analytics and KPI tracking
- **User Management**: User accounts, roles, and status management
- **Room Management**: Add, edit, and manage room listings
- **Booking Management**: View and manage all bookings
- **Reports & Analytics**: Revenue tracking and performance metrics
- **Support System**: Ticket management and customer support

## 🛠️ Technology Stack

- **Backend**: PHP 8+ with custom MVC framework
- **Database**: MySQL 8+
- **Frontend**: HTML5, TailwindCSS, JavaScript (ES6+)
- **Icons**: Font Awesome 6
- **Charts**: Chart.js for analytics
- **Server**: Apache/Nginx with mod_rewrite

## 📋 Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- mod_rewrite enabled
- PDO MySQL extension
- GD extension (for image handling)

## 🚀 Quick Start (5 Minutes)

### For XAMPP Users (Recommended)

1. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Start Apache and MySQL

2. **Automatic Database Setup**
   ```
   Visit: http://localhost/demo-pg-01-main/setup-database.php
   ```
   This automatically creates the database and imports all tables.

3. **Access Application**
   ```
   Homepage: http://localhost/demo-pg-01-main/
   Admin Panel: http://localhost/demo-pg-01-main/admin/login
   ```

4. **Default Admin Credentials**
   ```
   Email: admin@delhipg.com
   Password: Admin@2025
   ```
   ⚠️ **Change password immediately after first login!**

### Manual Installation

If you prefer manual setup or are using a different environment:

#### 1. Clone/Download the Project
```bash
git clone <repository-url> demo-pg-01-main
# Or download and extract to your web server directory
```

#### 2. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE delhi_modern_living;
exit

# Import schema
mysql -u root -p delhi_modern_living < database/schema.sql
```

#### 3. Configuration
```php
# Copy example and edit config/database.php
# Set your database credentials:
private $host = 'localhost';
private $username = 'root';
private $password = '';
private $database = 'delhi_modern_living';
```

### 4. Set Permissions
```bash
# Make uploads directory writable
chmod 755 uploads/
chmod 755 assets/

# Ensure .htaccess is readable
chmod 644 .htaccess
```

### 5. Web Server Configuration

#### Apache
Ensure mod_rewrite is enabled and .htaccess files are allowed.

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

## 🔐 Default Admin Account

After importing the database schema, you can login to admin panel with:

- **URL**: `http://your-domain.com/Delhi%20Modern%20Living/admin/login`
- **Email**: `admin@delhipg.com`
- **Password**: `Admin@2025` (change immediately after first login)

## 📁 Project Structure

```
Delhi Modern Living/
├── assets/                 # Static assets
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── images/            # Images and media
├── config/                # Configuration files
│   ├── database.php       # Database configuration (ignored in git)
│   └── database.example.php # Example database config for contributors
│   └── config.php         # Application configuration
├── controllers/           # MVC Controllers
│   ├── AuthController.php
│   ├── HomeController.php
│   ├── RoomController.php
│   ├── CartController.php
│   ├── CheckoutController.php
│   ├── OrderController.php
│   ├── ProfileController.php
│   └── Admin*/            # Admin controllers
├── core/                  # Core framework files
│   ├── Router.php         # URL routing
│   ├── Controller.php     # Base controller
│   └── Model.php          # Base model
├── database/              # Database files
│   └── schema.sql         # Database schema
├── models/                # MVC Models
│   ├── User.php
│   ├── Room.php
│   ├── Order.php
│   └── Cart.php
├── views/                 # MVC Views
│   ├── layouts/           # Layout templates
│   ├── home/              # Home page views
│   ├── auth/              # Authentication views
│   ├── rooms/             # Room views
│   ├── cart/              # Cart views
│   ├── checkout/          # Checkout views
│   ├── orders/            # Order views
│   ├── profile/           # Profile views
│   └── admin/             # Admin views
├── uploads/               # File uploads directory
├── .htaccess             # Apache rewrite rules
├── index.php             # Application entry point
└── README.md             # This file
```

## 🎨 Customization

### Styling
- Edit `assets/css/style.css` for custom styles
- Modify TailwindCSS classes in view files
- Update color scheme in `tailwind.config` sections

### Branding
- Replace logo files in `assets/images/`
- Update site name in `config/config.php`
- Modify footer content in `views/layouts/app.php`

### Features
- Add new controllers in `controllers/` directory
- Create corresponding models in `models/` directory
- Add routes in `index.php`
- Create views in appropriate `views/` subdirectories

## 💳 Payment Integration

The system supports multiple payment methods:

### Razorpay Integration
```php
# In config/config.php
define('RAZORPAY_KEY_ID', 'your_razorpay_key_id');
define('RAZORPAY_KEY_SECRET', 'your_razorpay_key_secret');
```

### Bank Transfer
Configure bank details in checkout view for manual transfers.

### Cash Payment
Allows payment on arrival for walk-in customers.

## 📧 Email Configuration

Configure SMTP settings in `config/config.php`:
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your_email@gmail.com');
define('SMTP_PASSWORD', 'your_app_password');
```

## 🔒 Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **SQL Injection Prevention**: PDO prepared statements
- **Password Security**: bcrypt hashing
- **Input Sanitization**: All user inputs are sanitized
- **Session Security**: Secure session management
- **File Upload Security**: Type and size validation

## 📱 Mobile Responsiveness

The website is fully responsive with:
- Mobile-first design approach
- Touch-friendly navigation
- Optimized images and loading
- Progressive enhancement

## 🚀 Performance Optimization

- **Caching**: Static file caching headers
- **Compression**: Gzip compression enabled
- **Image Optimization**: Lazy loading implementation
- **Database**: Indexed queries and optimized schema
- **Minification**: CSS and JS optimization ready

## 🐛 Troubleshooting

### Common Issues

1. **404 Errors**: Ensure mod_rewrite is enabled and .htaccess is readable
2. **Database Connection**: Check credentials in `config/database.php`
## ☁️ Publish to GitHub

This repo is ready for GitHub. We provide a `.gitignore` that excludes secrets and large/unnecessary files.

```bash
# From project root
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/<your-username>/<your-repo>.git
git push -u origin main
```

Make sure to create `config/database.php` locally from `config/database.example.php` before running the app.

3. **File Permissions**: Ensure uploads directory is writable
4. **PHP Errors**: Enable error reporting in development mode

### Debug Mode
```php
# In config/config.php
define('ENVIRONMENT', 'development'); // Enable error reporting
```

## 📈 Future Enhancements

- [ ] Real-time chat support
- [ ] Mobile app integration
- [ ] Advanced booking calendar
- [ ] Multi-language support
- [ ] Social media login
- [ ] Review and rating system
- [ ] Automated invoice generation
- [ ] SMS notifications
- [ ] Advanced analytics dashboard
- [ ] API for third-party integrations

## 📚 Documentation

- **[PROJECT_GUIDE.md](PROJECT_GUIDE.md)** - Comprehensive setup and usage guide
- **[README.md](README.md)** - This file (project overview)

## ✨ Recent Improvements (v2.0.0)

### What's New
- ✅ **Complete Admin Panel Redesign**: Modern dashboard with working navigation
- ✅ **UI Modernization**: Contemporary design with glassmorphism effects
- ✅ **Fixed All Admin Pages**: Users, Rooms, Bookings, Tickets, Reports, Settings
- ✅ **Better Error Handling**: Improved JavaScript and PHP error handling
- ✅ **Enhanced Security**: CSRF, XSS, and SQL injection protection verified
- ✅ **Consolidated Documentation**: Single comprehensive guide created
- ✅ **Production Ready**: Fully tested and ready to deploy

See [PROJECT_GUIDE.md](PROJECT_GUIDE.md) for complete details and setup instructions.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License. See LICENSE file for details.

---

**🎉 Ready to use! Visit [PROJECT_GUIDE.md](PROJECT_GUIDE.md) for detailed instructions.**

## 📞 Support

For support and questions:
- Email: support@delhimodernliving.com
- Phone: +91-9876543210
- Documentation: [Project Wiki]

## 🙏 Acknowledgments

- TailwindCSS for the utility-first CSS framework
- Font Awesome for the icon library
- Chart.js for analytics visualization
- PHP community for excellent documentation

---

**Delhi Modern Living** - Making accommodation booking simple and secure.

*Built with ❤️ for the modern traveler*
