## Last Updated / Ostatnia aktualizacja
This README was last updated on **11.03.2026**

---

# Serwis ATM – System Logowania i Raportowania Serwisowego  
# ATM Service – Login and Service Reporting System

## 📌 Opis projektu / Project Description
**PL:**  
Jest to aplikacja webowa stworzona w **PHP, HTML, CSS i JavaScript** do zarządzania serwisantami bankomatów.  

Zawiera moduły:  
- Autoryzacja użytkowników  
- Resetowanie i zmiana hasła  
- Wypełnianie raportów serwisowych (naprawa lub przegląd)  
- Wysyłanie raportów e-mailem z załącznikami (zdjęcia lub PDF)  
- Prosty interfejs z trybem jasnym/ciemnym  

**EN:**  
This is a **web application built with PHP, HTML, CSS, and JavaScript** for managing ATM service technicians.  

It includes modules:  
- User authentication  
- Password reset and change  
- Service report submission (repair or maintenance)  
- Sending service reports via email with attachments (photos or PDFs)  
- Simple interface with light/dark mode  

---

## ✨ Funkcje / Features
**PL:**  
- **System logowania** (`index.html + logowanie.php`)  
  - Bezpieczne hasła `password_hash` / `password_verify`  
  - Sesje chroniące panel serwisanta  
- **Odzyskiwanie hasła** (`forgor.html + odzysk.php`)  
  - Weryfikacja loginu i e-maila  
  - Jednorazowe hasło awaryjne (`backup`) wysyłane na e-mail  
  - Wymuszenie zmiany hasła po użyciu backupu  
- **Zmiana hasła** (`haslo.php`)  
  - Minimalna długość 6 znaków  
  - Wymagana przy pierwszym logowaniu lub po backupie  
- **Formularz serwisowy** (`formularz.php`)  
  - Wybór rodzaju serwisu: **naprawa** / **przegląd**  
  - Dynamiczne pola formularza zależne od wyboru  
  - Załącznik: zdjęcie lub PDF (obowiązkowy)  
  - Zapis w bazie danych i wysyłka e-mailem  
- **Tryb jasny/ciemny**  
  - Ikona Font Awesome: słońce/księżyc  
- **Responsywny design**  
  - Dostosowany do komputerów i urządzeń mobilnych  

**EN:**  
- **Login system** (`index.html + logowanie.php`)  
  - Secure password handling with `password_hash` / `password_verify`  
  - Session-based protection for technician panel  
- **Password recovery** (`forgor.html + odzysk.php`)  
  - Login + email verification  
  - One-time backup password sent to email  
  - Enforces password change after backup usage  
- **Password change** (`haslo.php`)  
  - Minimum 6 characters  
  - Required on first login or after using backup  
- **Service report form** (`formularz.php`)  
  - Choose service type: **repair** or **maintenance**  
  - Dynamic form fields based on selection  
  - Attach photo or PDF (mandatory)  
  - Save to database and send via email  
- **Light/Dark mode**  
  - Font Awesome sun/moon icon  
- **Responsive design**  
  - Works on desktop and mobile  

---

## 🛠️ Technologie / Technologies
**PL:**  
- Frontend: HTML, CSS (jasny/ciemny, responsywny), JavaScript, Google Fonts (Roboto), Font Awesome  
- Backend: PHP, MySQL/MariaDB  
- E-mail: PHP `mail()`  

**EN:**  
- Frontend: HTML, CSS (light/dark, responsive), JavaScript, Google Fonts (Roboto), Font Awesome  
- Backend: PHP, MySQL/MariaDB  
- Email: PHP native `mail()`  

---

## 🔐 Bezpieczeństwo / Security
**PL:**  
- Hasła przechowywane bezpiecznie (`password_hash()`)  
- Backup password przechowywany osobno  
- Sesje chronią panel i formularze  
- Prepared statements (`mysqli->prepare`) zabezpieczają przed SQL injection  
- Walidacja załączników: tylko obrazy i PDF  

**EN:**  
- Passwords stored securely (`password_hash()`)  
- Backup password stored separately  
- Sessions protect technician panel and forms  
- Prepared statements (`mysqli->prepare`) prevent SQL injection  
- File validation: only images and PDF allowed  

---

## 📂 Struktura plików / File Structure
```plaintext
/project
 ├── index.html        # Strona logowania / Login page
 ├── forgor.html       # Formularz odzyskiwania hasła / Password recovery form
 ├── odzysk.php        # Backend odzyskiwania hasła / Password recovery backend
 ├── logowanie.php     # Obsługa logowania / Login handling
 ├── haslo.php         # Zmiana hasła / Password change page
 ├── formularz.php     # Formularz raportu serwisowego / Service report form
 ├── script.js         # JS: dark mode toggle, dynamic form, file validation
 └── style.css         # Style: responsive design, light/dark mode
