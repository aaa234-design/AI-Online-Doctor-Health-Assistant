# CareBridge — AI Online Doctor & Health Assistant

A PHP 8+ / MySQL starter application for patient intake, symptom recording, vital-sign entry, medicine reference lookup, and clinician review.

## Features
- Patient registration with automatic patient ID
- Symptom and medical-history intake
- Manually entered temperature and blood pressure
- Searchable medicine reference library
- Clinician review queue and status updates
- Responsive dashboard UI

## Requirements
- PHP 8.1+, MySQL 8+, Apache (XAMPP/WAMP/LAMP)
- PDO MySQL extension enabled

## Setup (XAMPP)
1. Copy this repository into `htdocs/carebridge`.
2. Create a MySQL database named `carebridge`.
3. Import `database/schema.sql` using phpMyAdmin.
4. Update database credentials in `config.php`.
5. Open `http://localhost/carebridge/`.

## Important safety and privacy notes
This is an educational starter, not a medical device or production-ready clinical system. It does not diagnose, prescribe, calculate doses, or recommend medicines from symptoms. The symptom form records information for clinician review; medicine entries are reference information only. A qualified clinician must make all diagnosis and treatment decisions.

Temperature and blood pressure must be measured using suitable external devices and entered by the user. For severe chest pain, difficulty breathing, stroke symptoms, fainting, or uncontrolled bleeding, seek emergency medical care immediately.

Before real patient use, add secure authentication and role-based authorization, CSRF protection, HTTPS, encryption, consent/privacy notices, audit logging, backups, retention controls, and applicable legal/regulatory review. Do not deploy publicly with the demo admin page unprotected.

## Demo clinician page
The starter includes a simple review page for local development. Add authentication and authorization before deployment.
