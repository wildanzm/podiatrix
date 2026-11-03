<div align="center">

# Podiatrix

**Diabetic foot screening application based on Inlow's 60-Second Diabetic Foot Screen.**

Podiatrix is a responsive web application that digitizes the Inlow's 60-Second Diabetic Foot Screen. Healthcare professionals and patients complete the exam step by step, and the app calculates the screening score, assigns one of five risk categories, and generates care recommendations. Patients can also book appointments with a practice and download a PDF report of their results.

<br />

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Flowbite](https://img.shields.io/badge/Flowbite-3.1-1C64F2?style=for-the-badge&logo=flowbite&logoColor=white)
![License: MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

</div>

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Clinical Logic](#clinical-logic)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Running the Application](#running-the-application)
- [Demo Accounts](#demo-accounts)
- [Project Structure](#project-structure)
- [Testing](#testing)
- [Security](#security)
- [Medical Disclaimer](#medical-disclaimer)
- [License](#license)

---

## Features

- **Multi-step assessment wizard.** A guided physical examination flow that works on desktop, tablet, and mobile browsers.
- **Automated scoring.** The app computes the total Inlow's screening score as soon as the exam is complete.
- **Risk classification.** Patients fall into one of five risk categories (Sangat Rendah, Rendah, Sedang, Tinggi, Darurat), each with a matching recommendation.
- **Appointment scheduler.** Patients view provider availability and book a slot. Each booking receives a queue number.
- **Overbooking protection.** Bookings use database row locking (`lockForUpdate`), so a slot cannot exceed its quota under concurrent requests.
- **PDF reports.** Clinical-style A4 portrait reports are generated from print-styled Blade templates with DomPDF.
- **Rate limiting.** Screening and booking endpoints reject repeated and automated submissions.
- **Role-based access control.** Separate `admin` and `patient` portals, managed with Spatie Laravel Permission.

## Tech Stack

| Layer | Technology | Purpose |
| --- | --- | --- |
| Backend framework | [Laravel 12](https://laravel.com) | Routing, ORM, queues, validation |
| Reactive UI | [Livewire 3](https://livewire.laravel.com) with Volt and [Flux UI](https://fluxui.dev) | Component-driven interface, single-file components |
| Styling | [Tailwind CSS 4](https://tailwindcss.com) | Utility-first CSS, compiled with `@tailwindcss/vite` |
| UI components | [Flowbite 3.1](https://flowbite.com) and Flux UI | Accessible interface components |
| Build tool | [Vite](https://vitejs.dev) | Asset bundling and dev server |
| PDF generation | [barryvdh/laravel-dompdf 3.1](https://github.com/barryvdh/laravel-dompdf) | HTML-to-PDF reports |
| Database | MySQL | Transactional storage with row-lock support |
| Authorization | [Spatie Laravel Permission 7.2](https://spatie.be/docs/laravel-permission) | Roles and permissions |

## Clinical Logic

The scoring and classification rules live in `ScreeningService.php`.

### Score composition

The total score adds the maximum points from both feet across seven criteria.

| Criterion | Max points | What it covers |
| --- | --- | --- |
| Skin condition | 3 | Dryness, fungal infection, calluses, ulcer history |
| Nail condition | 3 | Discoloration, thickness, ingrown nails |
| Sensation | 4 | 10g monofilament test and symptom checklist (numbness, tingling, burning) |
| Vascular / PAD | 12 | Pain (3), rubor (3), temperature (3), pedal pulse (3) |
| Deformity | 3 | Structural anomalies, Charcot joints, amputations |
| Range of motion | 3 | Ankle joint flexibility and stability |
| Footwear | 3 | Fit, wear, and protection |

### Risk classification

The service checks conditions from the most severe category to the least severe and returns the first match.

| Category | Condition | Recommended action |
| --- | --- | --- |
| Darurat (Emergency) | Active ulcer, or active Charcot foot | Immediate referral to a tertiary wound clinic |
| Tinggi (High) | (LOPS or PAD) and (ulcer history or amputation history) | Referral to a vascular or foot specialist, inspection every 1 to 3 months |
| Sedang (Moderate) | Any two of LOPS, PAD, or deformity | Protective footwear prescription, inspection every 3 to 6 months |
| Rendah (Low) | LOPS or PAD | Patient education, inspection every 6 to 12 months |
| Sangat Rendah (Very Low) | None of the above | Self-care education, annual inspection |

LOPS stands for loss of protective sensation. PAD stands for peripheral arterial disease.

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- [Composer](https://getcomposer.org)
- Node.js 18 or higher, with npm
- MySQL (SQLite also works for local development)

### Installation

1. Clone the repository.

   ```bash
   git clone https://github.com/wildannzm/podiatrix.git
   cd podiatrix
   ```

2. Install PHP dependencies.

   ```bash
   composer install
   ```

3. Install frontend dependencies.

   ```bash
   npm install
   ```

4. Create the environment file and generate the application key.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Set your database credentials in `.env`.

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=podiatrix
   DB_USERNAME=root
   DB_PASSWORD=your_secure_password
   ```

6. Run migrations and seeders. The seeders create the default roles and demo accounts.

   ```bash
   php artisan migrate --seed
   ```

7. Build the frontend assets.

   ```bash
   npm run build
   ```

### Running the Application

Start the web server, queue worker, and Vite dev server with one command:

```bash
composer dev
```

To run them separately, use three terminals:

```bash
php artisan serve          # web server
php artisan queue:listen   # queue worker
npm run dev                # Vite dev server
```

The application is available at <http://127.0.0.1:8000>.

## Demo Accounts

The seeders create two accounts, one per role. Run `php artisan migrate --seed` or `php artisan db:seed` if they are missing.

| Role | Email | Password | Access |
| --- | --- | --- | --- |
| Admin (healthcare worker) | `admin@podiatrix.com` | `admin123` | Manage practice schedules, view patient queues, perform screenings, write clinical notes, download PDF reports |
| Patient | `user@podiatrix.com` | `user1234` | Self-screening, appointment booking, queue tracking, screening history, diabetic education resources |

> [!WARNING]
> These credentials are for local development only. Remove or change them before any deployment.

## Project Structure

The project follows the standard Laravel layout.

```text
podiatrix/
├── app/            # Application code (models, Livewire components, services)
├── bootstrap/      # Framework bootstrap files
├── config/         # Configuration files
├── database/       # Migrations, factories, seeders
├── public/         # Web root and compiled assets
├── resources/      # Blade views, CSS, JavaScript
├── routes/         # Route definitions
├── storage/        # Logs, cache, generated files
├── tests/          # Automated tests
└── .github/        # GitHub Actions workflows
```

## Testing

Run the test suite with PHPUnit through Artisan:

```bash
php artisan test
```

## Security

- Screening and booking endpoints are rate limited.
- Booking quotas are enforced with database row locks.
- Access is restricted by role (`admin` and `patient`).
- Keep `.env` out of version control, and use strong credentials in every non-local environment.

To report a vulnerability, open a private security advisory on the [Security tab](https://github.com/wildannzm/podiatrix/security) of the repository instead of filing a public issue.

## Medical Disclaimer

Podiatrix is a screening aid. It does not diagnose conditions or replace the judgment of a qualified healthcare professional. Consult a clinician for diagnosis and treatment decisions.

## License

Distributed under the MIT License. See [LICENSE](LICENSE) for details.

---

<div align="center">

Maintained by [Wildan Zhilal Manafi](https://github.com/wildannzm)

</div>
