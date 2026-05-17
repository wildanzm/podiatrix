# Podiatrix — Modern Diabetic Foot Screening Application

## 🌟 About Podiatrix

**Podiatrix** is a high-performance, responsive web application built to digitize and automate diabetic foot screenings using the clinically standardized **Inlow’s 60-Second Diabetic Foot Screen**. Designed for healthcare professionals and self-screening patients, Podiatrix accelerates assessment speed, minimizes clinical guesswork, provides real-time risk classification, and auto-generates dynamic care recommendations.

By digitizing physical examinations of skin, nail conditions, sensory response, vascular flow, and deformities, Podiatrix transforms complex medical checklists into a flawless multi-step user experience.

---

## ✨ Key Features

- 📋 **Multi-Step Assessment Wizard**: Step-by-step physical examination flow optimized for desktop, tablet, and mobile browsers.
- 🧮 **Automated Score Calculation**: Employs standardized scoring logic to compute a total Inlow's screening score instantly.
- 🏷️ **Dynamic Risk Classification**: Classifies patients into 5 clinical risk categories (Sangat Rendah, Rendah, Sedang, Tinggi, Darurat) and generates automated, actionable recommendations.
- 📅 **Integrated Appointment & Practice Scheduler**: Allows patients to view healthcare provider availability and book slots with instant queue number assignments.
- 🔒 **Safe Concurrency Booking Engine**: Implements database-level row locking (`lockForUpdate`) to prevent overbooking and guarantee absolute schedule quota integrity.
- 🖨️ **Professional PDF Report Downloads**: Generates elegant, clinical-grade A4 Portrait reports using custom print-styled blade structures powered by DomPDF.
- 🚦 **Rate Limiting Security**: Restricts malicious double-clicks and automated submissions on screenings and bookings.

---

## 🛠️ Technical Stack & Architecture

Podiatrix is engineered with a modern, high-speed **TALL Stack** paired with strict concurrency and state management rules:

| Layer | Component | Description |
|---|---|---|
| **Core Framework** | **Laravel 12.0** | Robust, modern backend ecosystem with optimized container pipelines. |
| **State & Interactivity**| **Livewire 3 (Volt & Flux UI)** | Component-driven reactive UI. Volt allows single-file Livewire components. |
| **Front-End Styling** | **Tailwind CSS v4.0** | Next-generation utility-first styling with lightning-fast `@tailwindcss/vite` compiler. |
| **Component Kit** | **Flowbite 3.1 & Flux UI** | Modern, highly accessible UI foundations. |
| **PDF Engine** | **Barryvdh DomPDF 3.1** | HTML-to-PDF conversion engine utilizing page-break control rules. |
| **Database** | **MySQL** | High-performance transactional persistence with row-locking support. |
| **Security & RBAC** | **Spatie Laravel Permission 7.2** | Strict Role-Based Access Control dividing system access into `admin` and `patient` portals. |

---

## 🩺 Clinical Logic: Inlow's 60-Second Screening Rules

Diabetic foot screenings compute a total score out of maximum assessment points and derive the final risk classification. The calculation logic in `ScreeningService.php` is strictly mapped as follows:

### 1. Inlow's Score Composition
The total score is the sum of maximum points from both feet across multiple criteria:
- **Skin Condition** (Max 3 pts): Dryness, fungal infections, calluses, or history of ulcers.
- **Nail Condition** (Max 3 pts): Discoloration, thickness, or ingrown nails.
- **Sensation** (Max 4 pts): Measured using a 10g monofilament test + symptom checklist (numbness, tingling, burning).
- **Vascular / PAD** (Max 12 pts): Sum of Pain (Max 3), Rubor / Redness (Max 3), Temperature (Max 3), and Pedal Pulse (Max 3).
- **Deformity** (Max 3 pts): Structural anomalies, Charcot joints, or amputations.
- **Range of Motion / ROM** (Max 3 pts): Ankle joint flex stability.
- **Footwear** (Max 3 pts): Fit, wear quality, and protection.

### 2. Clinical Risk Classification Priority
Risk is evaluated from highest to lowest severity, triggering immediate warnings for high-risk patients:

| Classification | Triggering Clinical Conditions | Recommended Actions |
|---|---|---|
| **🚨 Darurat (Emergency)** | Active ulcer OR Active Charcot foot | Immediate tertiary wound clinic referral. |
| **⚠️ Tinggi (High)** | (LOPS OR PAD) AND (History of ulcer OR History of amputation) | Specialized vascular/foot clinic referral, inspect every 1-3 months. |
| **⚡ Sedang (Moderate)** | Any 2 combinations of: LOPS, PAD, or Deformity | Protective footwear prescription, check every 3-6 months. |
| **🟢 Rendah (Low)** | LOPS (Loss of Sensation) OR PAD (Vascular issues) | Patient education, inspect every 6-12 months. |
| **✅ Sangat Rendah (Very Low)** | None of the above conditions met | Patient self-care education, check annually. |

---

## 🚀 Getting Started

Follow these instructions to run a local copy of Podiatrix on your system.

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js (v18+) & npm
- MySQL or SQLite database

### Installation & Setup

1. **Clone the repository:**
   ```sh
   git clone https://github.com/wildanzm/podiatrix.git
   cd podiatrix
   ```

2. **Install PHP dependencies:**
   ```sh
   composer install
   ```

3. **Install and compile frontend assets:**
   ```sh
   npm install
   npm run build
   ```

4. **Prepare Environment File:**
   Copy the example environment configuration:
   ```sh
   cp .env.example .env
   ```
   Generate the application security key:
   ```sh
   php artisan key:generate
   ```

5. **Configure Database Settings:**
   Open `.env` and set up your MySQL connection details:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=podiatrix
   DB_USERNAME=root
   DB_PASSWORD=your_secure_password
   ```

6. **Run Database Migrations & Seeds:**
   This seeds the default clinical roles and ready-to-use testing accounts:
   ```sh
   php artisan migrate --seed
   ```

---

## 🔐 Default Demo Accounts

Use the following seeded accounts to explore Podiatrix’s role-based dashboard portals:

> [!NOTE]
> Make sure your database is seeded using `php artisan db:seed` or `php artisan migrate --seed`.

### 🩺 Healthcare Worker / Admin Portal
- **Email**: `admin@podiatrix.com`
- **Password**: `admin123`
- **Permissions**: Manage practice schedules, access active patient queues, perform physical screenings, write clinical notes, and download official PDF screening reports.

### 👤 Patient Portal
- **Email**: `user@podiatrix.com`
- **Password**: `user1234`
- **Permissions**: Complete self-screening walkthroughs, book practice appointments, track real-time queue assignments, view screening history, and access diabetic education resources.

---

## 💻 Running Locally

To start all necessary servers (Laravel backend, queue worker, and Vite assets compiler) simultaneously, run:
```sh
composer dev
```
*Alternatively, you can run them in separate terminal windows:*
- **Web Server**: `php artisan serve`
- **Queue Worker**: `php artisan queue:listen`
- **Vite Compiler**: `npm run dev`

The application will be accessible at [http://127.0.0.1:8000](http://127.0.0.1:8000).