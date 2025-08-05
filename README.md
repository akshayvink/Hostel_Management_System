.# Hostel Management System

This is a web-based application for managing a hostel. It provides separate interfaces for administrators and hostelers.

## Features

*   **Admin Dashboard:** Admins can view and manage hosteler details, approve fee payments, manage rooms, and view complaints.
*   **Hosteler Dashboard:** Hostelers can view their profile, pay fees, select rooms, and submit complaints.
*   **User Authentication:** Secure login and registration for both admins and hostelers.
*   **Complaint Management:** Hostelers can submit complaints, and admins can track and resolve them.
*   **Room Management:** Admins can manage room capacity and assignments.
*   **Fee Management:** Admins can approve fee payments and hostelers can view their fee status.
*   **Announcements:** Admins can post announcements that are visible to all users.
*   **OTP Verification:** Email-based OTP verification for password resets.

## Technologies Used

*   **Frontend:** HTML, CSS, JavaScript
*   **Backend:** PHP
*   **Database:** MySQL
*   **Email:** PHPMailer

## Database Schema

The application uses a MySQL database named `hostel_management`.

### Tables and Columns

*   **`admins`**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
    *   `name` (VARCHAR)
    *   `phone` (VARCHAR)
    *   `email` (VARCHAR, UNIQUE)
    *   `password` (VARCHAR)

*   **`hostelers`**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
    *   `full_name` (VARCHAR)
    *   `father_name` (VARCHAR)
    *   `mother_name` (VARCHAR)
    *   `phone` (VARCHAR, UNIQUE)
    *   `email` (VARCHAR, UNIQUE)
    *   `dob` (DATE)
    *   `hosteler_type` (VARCHAR)
    *   `id_card` (VARCHAR) - Path to the uploaded ID card image
    *   `aadhar` (VARCHAR) - Path to the uploaded Aadhar card image
    *   `password` (VARCHAR)
    *   `room_number` (VARCHAR)
    *   `bed_type` (VARCHAR)
    *   `fee_status` (VARCHAR)

*   **`rooms`**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
    *   `room_number` (VARCHAR, UNIQUE)
    *   `room_type` (VARCHAR)
    *   `max_capacity` (INT)
    *   `current_occupants` (INT)

*   **`complaints`**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
    *   `name` (VARCHAR)
    *   `email` (VARCHAR)
    *   `room_number` (VARCHAR)
    *   `complaint_type` (VARCHAR)
    *   `description` (TEXT)
    *   `status` (VARCHAR)
    *   `created_at` (TIMESTAMP)

*   **`announcements`**
    *   `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
    *   `message` (TEXT)
    *   `created_at` (TIMESTAMP)

## Setup

1.  **Prerequisites:**
    *   A web server (e.g., Apache)
    *   PHP
    *   MySQL
    *   Composer

2.  **Database:**
    *   Create a MySQL database named `hostel_management`.
    *   Import the provided SQL file or manually create the tables using the schema above.

3.  **Project Files:**
    *   Clone or download the project files into the document root of your web server (e.g., `htdocs` for XAMPP).

4.  **Dependencies:**
    *   This project uses PHPMailer for sending emails. Install it using Composer:
        ```bash
        composer install
        ```

5.  **Configuration:**
    *   **Database:** Update the database connection details in `db_connection.php` if they are different from the defaults.
    *   **PHPMailer:**
        *   Open the files that use PHPMailer (e.g., `send_otp.php`, `forgot_password.php`).
        *   Locate the PHPMailer configuration section.
        *   You will need to configure the following settings with your SMTP server details:
            *   `$mail->Host`: Your SMTP server (e.g., `smtp.gmail.com`).
            *   `$mail->Username`: Your SMTP username (usually your email address).
            *   `$mail->Password`: Your SMTP password or an app-specific password.
            *   `$mail->Port`: The SMTP port (e.g., 587 for TLS, 465 for SSL).
            *   `$mail->setFrom()`: The email address and name that the email will be sent from.

## Usage

*   **Admin:**
    *   Register a new admin account at `adminregister.php`.
    *   Login at `adminlogin.html`.
*   **Hosteler:**
    *   Register a new hosteler account at `hostelerregister.html`.
    *   Login at `hostelerlogin.html`.

## Contributing

Contributions are welcome! Please feel free to submit a pull request.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.# Hostel_Management_System
