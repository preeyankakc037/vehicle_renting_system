# 🚙 Vehicle Renting System (PATHEK)

A modern peer-to-peer vehicle rental platform built using PHP (MVC), MySQL, and Bootstrap 5.

<p align="center">
  <img src="https://github.com/user-attachments/assets/1dc9b2fb-f230-4193-9aad-e8be8618235a" width="32%" />
  <img src="https://github.com/user-attachments/assets/35ae6150-ad4b-4c2b-89ac-1b7e30128a89" width="32%" />
  <img src="https://github.com/user-attachments/assets/3ade0a1c-0749-45e5-9a87-b9c2ae9ad86e" width="32%" />
</p>

---

## 📌 Project Definition

**PATHEK** is a full-stack web-based vehicle rental system designed as a peer-to-peer marketplace where renters can book vehicles, owners can list and manage their vehicles, and admins can control and moderate the entire ecosystem.  

The platform implements secure authentication with OTP verification, structured MVC architecture, and a normalized relational database to ensure scalability, security, and clean system design.

---

## 📖 Project Description

PATHEK was developed as a university project focusing on structured software design, MVC architecture, and real-world business logic implementation.

### 👥 User Roles

The system supports three distinct roles:

- **Admin**
  - Controls the entire system
  - Approves vehicles and verifies owners
  - Monitors system statistics
  - Manages users and operations

- **Owner**
  - Lists vehicles (Cars, Bikes, Scooters)
  - Performs full CRUD operations
  - Manages bookings
  - Views revenue-related activity

- **Renter**
  - Browses available vehicles
  - Books vehicles
  - Manages personal bookings
  - Maintains wishlist and profile

---

## 🔐 Authentication & Security

- Email-based OTP verification during registration
- Secure password hashing using `password_hash()`
- Role-based session control
- SQL Injection prevention using prepared statements (`bind_param`)
- XSS prevention using `htmlspecialchars()`
- Secure SMTP configuration using PHPMailer

⚠️ To run this project, you must:
- Configure your own **SMTP credentials**
- Add your Gmail & App Password inside `SMTP.php`

---

## 🚙 Core Functional Features

### 🏷️ Vehicle Management (Owner CRUD)
- Add vehicle listings (Pending admin approval)
- Edit and update vehicle details
- Delete vehicle listings
- Upload vehicle images

### 📅 Booking Workflow
- Date conflict detection using overlap logic
- Booking status: Pending → Confirmed / Rejected
- Real-time booking updates for renters
- Owner booking management dashboard

### 🛠️ Admin Control Panel
- Owner verification queue
- Vehicle approval system
- Live system statistics
- Full ecosystem management

---

## 🧠 Technical Architecture

### 🏗️ MVC Pattern
- **Models** → Database interactions  
- **Controllers** → Business logic  
- **Views** → Frontend rendering  

### 🗄️ Database
- MySQL (Normalized Schema)
- Foreign key relationships (User → Vehicle → Booking)
- ER Model based structured design

Database file available in:
```
/SQL/id25123827.sql
```

Import directly into **phpMyAdmin** to initialize the system.

---

## 🎨 UI & Design

- Bootstrap 5 framework
- Custom Green Branding Theme (#2E6F40)
- Responsive design
- Clean grid-based layout
- External CSS customization
- Modern typography (Outfit font)

---

## ⚙️ How to Run

1. Clone the repository
2. Import the SQL file into phpMyAdmin
3. Configure `SMTP.php` with:
   - Gmail address
   - App password
   - SMTP configuration
4. Run using a local server (XAMPP / WAMP / MAMP)

---

## 🌟 What Makes This Project Stand Out?

- Real multi-role architecture (Admin / Owner / Renter)
- OTP-based secure registration flow
- Clean MVC implementation
- Structured booking conflict detection logic
- Admin moderation system
- Full relational database design
- University-grade system documentation
- Production-style authentication flow

This is not just CRUD — it simulates a real rental marketplace ecosystem.

---

## 🏷️ Badges

![PHP](https://img.shields.io/badge/PHP-Backend-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5-purple)
![MVC Architecture](https://img.shields.io/badge/Architecture-MVC-green)
![PHPMailer](https://img.shields.io/badge/Email-PHPMailer-red)
![OTP Authentication](https://img.shields.io/badge/Security-OTP-yellow)
![Project Type](https://img.shields.io/badge/Type-Full%20Stack%20Web%20App-success)

---

## 📌 Summary

PATHEK demonstrates a complete multi-user vehicle rental ecosystem built using PHP and MySQL with secure authentication, booking logic, admin moderation, and clean MVC architecture.

It reflects practical system design, database modeling, and real-world business workflow implementation.

---

**Author:** Priyanka Khatri  
**Project:** Vehicle Rental System (PATHEK)  
**University Project – Full Stack Development**
