## Last Updated
This README was last updated on **11.09.2025**

---

# ATM Service – Login and Service Reporting System

## Project Description
This project is a **web application built with PHP, HTML, CSS, and JavaScript** for managing ATM service technicians.  

It includes modules for:
- User authentication with password verification  
- Password reset and change  
- Service report submission (repair or maintenance)  
- Sending service reports via email with attachments (photos)  
- A simple user interface with light/dark mode  


## Features
- **Login system** with `password_hash` and `password_verify`  
- **Password reset** with the ability to set a new password  
- **Service report form**:
  - Select type of service (repair or maintenance)  
  - Dynamic fields depending on selection  
  - Option to attach a photo  
  - Save data to the database and send a report via email  
- **Light/Dark mode toggle** using a Font Awesome icon  
- **Responsive design** for mobile and desktop devices  


## Technologies
**Frontend**:  
- HTML  
- CSS  
- JavaScript  
- Google Fonts  
- Font Awesome  

**Backend**:  
- PHP  
- MySQL/MariaDB  

**Optional**:  
- PHPMailer (for improved email sending)  


## Security
- Passwords are stored securely using `password_hash`  
- An additional `backup` field provides a fallback password  
- Sessions protect access to the service technician panel  


## File Structure
**/project**
  - `index.html` – Login page  
  - `logowanie.php` – Login handling  
  - `forgor.html` – Password recovery form (WIP)  
  - `haslo.php` – Password change page  
  - `formularz.php` – Service report form  
  - `style.css` – Stylesheet  
