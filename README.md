# 💻 Internet Cafe Management System

This is a PHP-based management system for tracking internet café computer usage, user registration, and administrative control.

---

## 📌 Table of Contents

- [Project Structure](#project-structure)
- [Requirements](#requirements)
- [Features](#features)
- [How to Run](#how-to-run)
- [License](#license)
- [Credits](#credits)

---
## 📁 Project Structure

```
Internet-Cafe-Management-System/
├── database/ # Contains the SQL dump for MySQL database
├── includes/ # Database connection & layout includes
├── public/ # All frontend + backend PHP scripts
├── auth/ # Login/logout functionalities
├── reports/ # Project report and slides
├── .gitignore
└── README.md
```
---

## ⚙️ Requirements

- PHP >= 7.4
- MySQL or Oracle
- Web server: XAMPP / WAMP / Apache

---

## 🧠 Features

- Add/Delete/Update users, computers, employees
- Track computer usage per session
- Admin UI and User UI
- Authentication for both users and admin

---

## 🚀 How to Run

1. Clone this repo:
```bash
git clone https://github.com/KonnMjn/IS210-InternetCafe-ManagementSystem.git
cd IS210-InternetCafe-ManagementSystem
```
2. Import `DBS_INTERNET.sql` into MySQL
3. Configure `includes/dbconnect.php` with your database credentials
4. Run via `localhost/public/Login.php`
---


## 📄 License
For educational use only. All rights reserved to the development team.

---

## 👨‍🏫 Credits

Phạm Đông Hưng – 22520521

Lương Anh Huy - 22520550

Phan Công Minh - 22520884

Hồng Khải Nguyên - 22520967

Instructor: Nguyễn Hồ Duy Trí

---
