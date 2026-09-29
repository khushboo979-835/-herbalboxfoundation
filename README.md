# Seva Arogya & Shiksha Foundation - Complete NGO Web Platform & Admin CMS

A production-ready, modern, humanitarian NGO Web Platform built with **Core PHP 8+**, **MySQL 8+**, **Bootstrap 5**, **Chart.js**, and **Font Awesome 6**.

---

## 🌟 Key Highlights & Core Capabilities

- **100% Dynamic Core PHP 8+ Architecture**: Zero node/framework dependencies; runs immediately on any standard PHP 8.x + MySQL hosting (XAMPP, WAMP, Hostinger, cPanel, Cloudways).
- **Comprehensive Healthcare Verticals**: Free Mega Medical Camps, Blood Donation Drives, Eye Care & Cataract Relief, Dental Camps, Diagnostic Lab Networks, and Free Medicine Distribution.
- **AYUSH & Multi-System Medicine**: Dedicated modules for Ayurveda, Homeopathy, Allopathy, and daily Yoga & Meditation camps.
- **NCERT School Education & MOUs (Classes 1st - 12th)**: School partnership directory, downloadable public MOUs, student kit distribution, and career guidance workshops.
- **Online Donation & 80G Tax Exemption System**: Preset amounts (₹500, ₹1000, ₹2500, ₹5000, Custom), PAN card capture, instant printable Section 80G tax receipt generation, and Razorpay payment readiness.
- **Volunteer Onboarding & Emergency Blood Registry**: Comprehensive application workflows with interest tracking, resume attachments, and automated email dispatches.
- **Executive Admin Dashboard**: Real-time KPI summary, monthly donation trends with Chart.js, live search, 1-click CSV data exports, and audit trails.
- **1-Click MySQL Database Backup Tool**: Export complete schema and records directly from the admin console.
- **Hardened Security**: PDO prepared statements, CSRF verification tokens, anti-brute force login lockouts, password hashing (`PASSWORD_DEFAULT`), and `.htaccess` file upload execution blocking.

---

## 📁 Complete Folder Structure

```text
/ngoschool
│
├── /admin                          # Secure Administrative Console
│   ├── /about                      # About Us, Trustees & Audit Reports CRUD
│   ├── /activity-logs              # Security & Activity Audit Trail
│   ├── /backup                     # 1-Click Database SQL Backup Tool
│   ├── /blog                       # Blog & News CMS
│   ├── /doctors                    # Partner Doctors Directory CRUD
│   ├── /donations                  # 80G Donations & Receipt Generator
│   ├── /enquiries                  # Contact Messages Helpdesk Inbox
│   ├── /event-registrations        # Event Attendee Passes & CSV Export
│   ├── /events                     # Mega Camps & Events CRUD
│   ├── /gallery                    # Media Photo Gallery Manager
│   ├── /home                       # Hero Banners & Sliders CRUD
│   ├── /hospitals                  # Hospital & Diagnostic Labs CRUD
│   ├── /impact                     # Dynamic Impact Counters CRUD
│   ├── /includes                   # Admin Header, Sidebar, Navbar, Footer & Guard
│   ├── /mou                        # MOU Document Management & PDF Upload
│   ├── /partners                   # Partnership Proposals & CSR Review
│   ├── /products                   # Ayurvedic Herbal Store CRUD
│   ├── /programs                   # Social Verticals & Programs CRUD
│   ├── /schools                    # School Partners & MOUs CRUD
│   ├── /seo                        # Search Engine Optimization Settings
│   ├── /settings                   # Master Site Settings & Payment Keys
│   ├── /testimonials               # Beneficiary Stories & Star Ratings
│   ├── /users                      # Admin Users & Role Permissions
│   ├── /videos                     # YouTube Video Embeds
│   ├── /volunteers                 # Volunteer Network & Status Workflow
│   ├── dashboard.php               # Executive KPI Dashboard & Analytics
│   ├── index.php                   # Admin Root Router
│   ├── login.php                   # Secure Login with Brute-Force Lockout
│   └── logout.php                  # Session Termination
│
├── /assets                         # Frontend & Admin Styling Assets
│   ├── /css
│   │   ├── admin.css               # Dashboard Styling & Responsive Tables
│   │   └── style.css               # Modern Humanitarian Frontend Theme
│   └── /js
│       ├── admin.js                # Dynamic Slug, Search & Modals
│       └── main.js                 # Counter Animations & Interactive Selectors
│
├── /config                         # Core System Configurations
│   ├── config.php                  # Base URL Resolution & Session Manager
│   ├── constants.php               # Global Application Constants & Upload Limits
│   └── database.php                # Resilient PDO Database Connection Manager
│
├── /database                       # Database Schemas & Migrations
│   └── schema.sql                  # MySQL 8.0+ Schema with Rich Seed Data
│
├── /includes                       # Frontend Shared Components & Helpers
│   ├── auth.php                    # Authentication & Session Helpers
│   ├── csrf.php                    # CSRF Token Generator & Validator
│   ├── footer.php                  # Dynamic Footer & WhatsApp Widget
│   ├── functions.php               # Helpers, Sanitization, Uploader & Flash Messages
│   ├── header.php                  # SEO Meta, OpenGraph & Schema.org JSON-LD
│   ├── mail.php                    # Reusable HTML Email Service
│   └── navbar.php                  # Top Announcement Bar & Multi-Level Nav
│
├── /uploads                        # Uploaded Media & Documents (Protected)
│   ├── /blogs
│   ├── /documents
│   ├── /doctors
│   ├── /events
│   ├── /gallery
│   ├── /products
│   ├── /schools
│   ├── /team
│   ├── /volunteers
│   └── .htaccess                   # Blocks direct PHP script execution
│
├── index.php                       # Premium 25-Section Homepage
├── about.php                       # History, Mission, Vision, Trustees & Accreditations
├── healthcare.php                  # Healthcare Programs, Camps & Diagnostics
├── education.php                   # Class 1st - 12th NCERT Learning & Kits
├── ayush.php                       # Ayurveda, Homeopathy & Allopathic Panel
├── yoga.php                        # Daily Yoga, Pranayama & Meditation Camps
├── medical-camps.php               # Mega Health Camps Directory & Schedule
├── blood-donation.php              # Blood Drives & Emergency Donor Registry Form
├── doctors.php                     # Filterable Partner Doctors Network
├── hospitals.php                   # Hospitals & Diagnostic Centres Directory
├── schools.php                     # School Partnerships & MOU Directory
├── partnerships.php                # Institutional Partnership Application Form
├── mou.php                         # Public MOU Document Transparency Archive
├── events.php                      # Community Events & Camp Listings
├── event-details.php               # Event Landing Page & Free Pass Registration
├── donate.php                      # 80G Online Donation & Razorpay Checkout
├── volunteer.php                   # Volunteer Application with Skills & Resume
├── gallery.php                     # Filterable Photo & Video Gallery
├── blog.php                        # Knowledge Hub & Health Articles
├── blog-details.php                # Single Article with Social Sharing & Author Box
├── products.php                    # Ayurvedic Store & Order Inquiry
├── contact.php                     # Helpdesk Form & Google Maps Embed
├── privacy-policy.php              # Data Privacy & 80G Terms
├── terms.php                       # Terms of Service
├── disclaimer.php                  # Clinical & Content Disclaimer
├── 404.php                         # User-Friendly Error Page
├── robots.txt                      # Search Engine Crawler Directives
└── .htaccess                       # Security Headers & File Protection
```

---

## 🚀 Installation & Localhost Setup (XAMPP / WAMP)

### 1. Place the Project
Copy the `ngoschool` folder into your web server root directory:
- **XAMPP (Windows)**: `C:\xampp\htdocs\ngoschool`
- **cPanel / Hostinger**: `public_html/` or `public_html/ngoschool/`

### 2. Create the MySQL Database
1. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Create a new database named: `ngoschool_db` (Collation: `utf8mb4_unicode_ci`).
3. Click on the **Import** tab.
4. Select `database/schema.sql` and click **Go**.

### 3. Verify Database Credentials
Open `config/database.php` and verify the settings:
```php
private static string $host = '127.0.0.1';
private static string $dbName = 'ngoschool_db';
private static string $username = 'root';
private static string $password = '';
```
*(On Hostinger/cPanel, replace with your actual MySQL database name, username, and password)*.

---

## 🔐 Default Administrator Credentials

- **Admin Login URL**: `http://localhost/ngoschool/admin/login.php`
- **Username**: `admin` *(or `admin@ngoseva.org`)*
- **Password**: `admin123`
- **Role**: `superadmin`

> **Note**: Change the default admin password from **Admin Console → Admin Users** after first deployment.

---

## 💳 Payment Gateway (Razorpay) Configuration

1. Log into your [Razorpay Dashboard](https://dashboard.razorpay.com).
2. Go to **Settings → API Keys** and generate Key ID & Secret.
3. In the NGO Admin Console, navigate to **Site Settings → Payment Gateway**:
   - Set **Razorpay Key ID** (`rzp_live_...` or `rzp_test_...`)
   - Set **Razorpay Key Secret**

---

## 📧 SMTP & Email Notifications

1. In the NGO Admin Console, navigate to **Site Settings → System Settings**:
   - Primary Organization Email (`info@ngoseva.org`)
   - Donation Desk Email (`donate@ngoseva.org`)
2. All contact messages, volunteer applications, partnership proposals, and donation tax receipts trigger automatic templated HTML emails.

---

## 📍 Google Maps & WhatsApp Integration

1. **Google Maps**: In **Site Settings**, paste your standard Google Maps `<iframe>` `src` URL to update the interactive map on the contact page.
2. **Floating WhatsApp Widget**: In **Site Settings**, enter your 10-digit or international WhatsApp number (e.g. `+919876543210`) to activate the 1-click floating chat button.

---

## 🔒 Production Security Measures

- **No Plain-Text Passwords**: Strict `password_hash()` and `password_verify()` using `PASSWORD_DEFAULT`.
- **CSRF Defense**: Time-bounded 32-byte cryptographic tokens on every form.
- **SQL Injection Prevention**: 100% of queries use PDO prepared statements with parameterized binds.
- **Upload Hardening**: Mime-type verification (`finfo`), extension whitelisting, and `.htaccess` disabling script execution inside `uploads/`.
