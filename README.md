## Last Updated
This README was last updated on **22.09.2025**

---

# ATM Service – Login and Service Reporting System

## 📌 Project Description
This project is a **web application built with PHP, HTML, CSS, and JavaScript** for managing ATM service technicians.  

It includes modules for:
- User authentication with password verification  
- Password reset and change  
- Service report submission (repair or maintenance)  
- Sending service reports via email with attachments (photos)  
- A simple user interface with light/dark mode  

---

## ✨ Features
- **Login system** with `password_hash` and `password_verify`  
- **Password reset (forgor.html + odzysk.php)**  
  - Verification by login and email  
  - Emergency password (`backup`) sent to the registered email  
  - System enforces password change after login with backup password  
- **Password change (haslo.php)**  
  - Required on first login or after using backup password  
  - Minimum 6 characters validation  
- **Service report form (formularz.php)**  
  - Select service type (repair or maintenance)  
  - Dynamic form fields depending on selection  
  - Option to attach a photo (required)  
  - Save report to database and send via email with attachments  
- **Light/Dark mode toggle**  
  - Implemented with Font Awesome sun/moon icon  
- **Responsive design** for both desktop and mobile  

---

## 🛠️ Technologies
**Frontend**  
- HTML  
- CSS (light/dark mode, responsive design)  
- JavaScript  
- Google Fonts (Roboto)  
- Font Awesome  

**Backend**  
- PHP  
- MySQL/MariaDB  

**Email**  
- Native PHP `mail()`  

---

## 🔐 Security
- Passwords stored securely with `password_hash()`  
- Emergency `backup` password stored separately  
- Sessions used to protect technician panel and service forms  
- Prepared statements (`mysqli->prepare`) protect against SQL injection  

---

## 📂 File Structure
```plaintext
/project
 ├── index.html        # Login page
 ├── forgor.html       # Password recovery form
 ├── odzysk.php        # Backend for password recovery
 ├── logowanie.php     # Login handling
 ├── haslo.php         # Password change page
 ├── formularz.php     # Service report form
 └── style.css         # Stylesheet
