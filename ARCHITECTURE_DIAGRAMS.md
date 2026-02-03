# AMS - Apartment Management System
## Complete System Architecture & Database Diagrams

---

## Table of Contents
1. [System Architecture Overview](#system-architecture-overview)
2. [Complete System Architecture Diagram](#complete-system-architecture-diagram)
3. [Application Layer Architecture](#application-layer-architecture)
4. [Database Entity-Relationship Diagram (ERD)](#database-entity-relationship-diagram-erd)
5. [Detailed Entity Descriptions](#detailed-entity-descriptions)
6. [Relationship Specifications](#relationship-specifications)

---

## System Architecture Overview

The AMS (Apartment Management System) is built on the **Laravel PHP Framework** following the **MVC (Model-View-Controller)** architectural pattern with additional layers for enhanced separation of concerns.

### Technology Stack
| Layer | Technology |
|-------|------------|
| **Frontend** | Blade Templates, Tailwind CSS, Vite |
| **Backend** | Laravel 11 (PHP 8.x) |
| **Database** | MySQL/SQLite |
| **Authentication** | Laravel Breeze |
| **Authorization** | Spatie Laravel Permission |
| **PDF Generation** | DomPDF |
| **Excel Export** | Maatwebsite Excel |

---

## Complete System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                                   CLIENT LAYER                                       │
├─────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐                      │
│  │   Web Browser   │  │  Mobile Browser │  │   Admin Panel   │                      │
│  │  (Responsive)   │  │   (Tailwind)    │  │    Dashboard    │                      │
│  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘                      │
│           │                    │                    │                               │
│           └────────────────────┴────────────────────┘                               │
│                                │                                                     │
│                         HTTPS Requests                                               │
└────────────────────────────────┼────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              PRESENTATION LAYER                                      │
├─────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────────────────┐    │
│  │                        BLADE TEMPLATE ENGINE                                 │    │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐        │    │
│  │  │   Layouts   │  │ Components  │  │   Partials  │  │    Views    │        │    │
│  │  │  - admin    │  │  - buttons  │  │  - navbar   │  │  - admin/*  │        │    │
│  │  │  - guest    │  │  - cards    │  │  - sidebar  │  │  - super/*  │        │    │
│  │  │  - app      │  │  - forms    │  │  - footer   │  │  - tenant/* │        │    │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘        │    │
│  └─────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                      │
│  ┌────────────────────────────────────┐  ┌────────────────────────────────────┐    │
│  │         TAILWIND CSS               │  │              VITE                   │    │
│  │   - Utility Classes                │  │   - Asset Bundling                  │    │
│  │   - Custom Design System           │  │   - Hot Module Replacement          │    │
│  │   - Responsive Design              │  │   - Production Build                │    │
│  └────────────────────────────────────┘  └────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                               ROUTING LAYER                                          │
├─────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────────────────────────────┐    │
│  │                           ROUTE DEFINITIONS                                  │    │
│  │                                                                              │    │
│  │   routes/web.php                      routes/auth.php                        │    │
│  │   ┌─────────────────────────┐         ┌─────────────────────────┐           │    │
│  │   │  /admin/* (admin only)  │         │  /login                 │           │    │
│  │   │  /supervisor/*          │         │  /register              │           │    │
│  │   │  /tenant/*              │         │  /logout                │           │    │
│  │   │  /dashboard             │         │  /password/*            │           │    │
│  │   └─────────────────────────┘         └─────────────────────────┘           │    │
│  └─────────────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              MIDDLEWARE LAYER                                        │
├─────────────────────────────────────────────────────────────────────────────────────┤
│  ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────┐        │
│  │   Auth     │ │   Role     │ │  Locale    │ │  Timezone  │ │ User Status│        │
│  │ Middleware │ │ Middleware │ │ Middleware │ │ Middleware │ │ Middleware │        │
│  │            │ │            │ │            │ │            │ │            │        │
│  │ - Verify   │ │ - admin    │ │ - en       │ │ - Set TZ   │ │ - Check    │        │
│  │   Session  │ │ - supervisor│ │ - km      │ │   from DB  │ │   active   │        │
│  │ - Redirect │ │ - tenant   │ │            │ │            │ │   status   │        │
│  └────────────┘ └────────────┘ └────────────┘ └────────────┘ └────────────┘        │
└─────────────────────────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              CONTROLLER LAYER                                        │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────┐    │
│  │                           ADMIN CONTROLLERS                                  │    │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐        │    │
│  │  │  Dashboard   │ │   Tenant     │ │   Expense    │ │  FiscalPeriod│        │    │
│  │  │  Controller  │ │  Controller  │ │  Controller  │ │  Controller  │        │    │
│  │  └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘        │    │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐        │    │
│  │  │   Apartment  │ │    Floor     │ │BalanceSheet  │ │   Setting    │        │    │
│  │  │  Controller  │ │  Controller  │ │  Controller  │ │  Controller  │        │    │
│  │  └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘        │    │
│  │  ┌──────────────┐ ┌──────────────┐                                          │    │
│  │  │    User      │ │   Archived   │                                          │    │
│  │  │  Controller  │ │   Tenant     │                                          │    │
│  │  └──────────────┘ └──────────────┘                                          │    │
│  └─────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────┐    │
│  │                        SUPERVISOR CONTROLLERS                                │    │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐        │    │
│  │  │  Dashboard   │ │   Customer   │ │    Rental    │ │   Payment    │        │    │
│  │  │  Controller  │ │  Controller  │ │  Controller  │ │  Controller  │        │    │
│  │  └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘        │    │
│  └─────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────┐    │
│  │                          TENANT CONTROLLERS                                  │    │
│  │  ┌──────────────┐                                                           │    │
│  │  │  Dashboard   │                                                           │    │
│  │  │  Controller  │                                                           │    │
│  │  └──────────────┘                                                           │    │
│  └─────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────┐    │
│  │                         SHARED CONTROLLERS                                   │    │
│  │  ┌──────────────┐ ┌──────────────┐                                          │    │
│  │  │   Profile    │ │ ActivityLog  │                                          │    │
│  │  │  Controller  │ │  Controller  │                                          │    │
│  │  └──────────────┘ └──────────────┘                                          │    │
│  └─────────────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                                MODEL LAYER                                           │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│  ┌──────────────────────────────────────────────────────────────────────────────┐   │
│  │                          ELOQUENT ORM MODELS                                  │   │
│  │                                                                               │   │
│  │   ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐           │   │
│  │   │   User   │ │  Floor   │ │Apartment │ │  Tenant  │ │ Customer │           │   │
│  │   └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘           │   │
│  │                                                                               │   │
│  │   ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐           │   │
│  │   │  Rental  │ │ Payment  │ │ Account  │ │ Expense  │ │ Setting  │           │   │
│  │   └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘           │   │
│  │                                                                               │   │
│  │   ┌──────────┐ ┌──────────┐ ┌──────────┐                                     │   │
│  │   │  Fiscal  │ │ Balance  │ │ Activity │                                     │   │
│  │   │  Period  │ │SheetItem │ │   Log    │                                     │   │
│  │   └──────────┘ └──────────┘ └──────────┘                                     │   │
│  │                                                                               │   │
│  └──────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                      │
│  ┌──────────────────────────────────────────────────────────────────────────────┐   │
│  │                              HELPER CLASSES                                   │   │
│  │   ┌─────────────────────┐                                                    │   │
│  │   │   SettingsHelper    │ → Manages application settings from DB             │   │
│  │   └─────────────────────┘                                                    │   │
│  └──────────────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              DATABASE LAYER                                          │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│  ┌──────────────────────────────────────────────────────────────────────────────┐   │
│  │                            MySQL / SQLite                                     │   │
│  │  ┌───────────────────────────────────────────────────────────────────────┐   │   │
│  │  │                          DATABASE TABLES                               │   │   │
│  │  │                                                                        │   │   │
│  │  │  ┌─────────┐ ┌─────────┐ ┌──────────┐ ┌─────────┐ ┌───────────┐       │   │   │
│  │  │  │  users  │ │ floors  │ │apartments│ │ tenants │ │ customers │       │   │   │
│  │  │  └─────────┘ └─────────┘ └──────────┘ └─────────┘ └───────────┘       │   │   │
│  │  │                                                                        │   │   │
│  │  │  ┌─────────┐ ┌─────────┐ ┌──────────┐ ┌─────────┐ ┌───────────┐       │   │   │
│  │  │  │ rentals │ │payments │ │ accounts │ │expenses │ │  settings │       │   │   │
│  │  │  └─────────┘ └─────────┘ └──────────┘ └─────────┘ └───────────┘       │   │   │
│  │  │                                                                        │   │   │
│  │  │  ┌─────────────┐ ┌──────────────────┐ ┌───────────────┐               │   │   │
│  │  │  │fiscal_periods│ │balance_sheet_items│ │ activity_logs │               │   │   │
│  │  │  └─────────────┘ └──────────────────┘ └───────────────┘               │   │   │
│  │  │                                                                        │   │   │
│  │  │  ┌───────────────────┐ ┌────────────────────────┐                     │   │   │
│  │  │  │ permissions       │ │ roles / model_has_roles│ (Spatie)            │   │   │
│  │  │  └───────────────────┘ └────────────────────────┘                     │   │   │
│  │  └────────────────────────────────────────────────────────────────────────┘   │   │
│  └──────────────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                           EXTERNAL SERVICES                                          │
├─────────────────────────────────────────────────────────────────────────────────────┤
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐                      │
│  │   File Storage  │  │  PDF Generator  │  │  Excel Export   │                      │
│  │   (local/s3)    │  │    (DomPDF)     │  │  (Maatwebsite)  │                      │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘                      │
│                                                                                      │
│  ┌─────────────────┐  ┌─────────────────┐                                           │
│  │  Mail Service   │  │    Queue/Jobs   │                                           │
│  │   (SMTP/etc)    │  │   (Database)    │                                           │
│  └─────────────────┘  └─────────────────┘                                           │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Application Layer Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                           USER ROLE-BASED ACCESS                                     │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│   ┌─────────────────────────────────────────────────────────────────────────────┐   │
│   │                              ADMIN ROLE                                      │   │
│   │   Full system access - manages all aspects of the apartment complex         │   │
│   │                                                                              │   │
│   │   ├── Dashboard (Statistics & Overview)                                     │   │
│   │   ├── User Management (CRUD users, assign roles)                            │   │
│   │   ├── Floor Management (Create/Edit building floors)                        │   │
│   │   ├── Apartment Management (Units per floor)                                │   │
│   │   ├── Tenant Management (Direct building tenants)                           │   │
│   │   │   ├── Active Tenants                                                    │   │
│   │   │   ├── Archived Tenants (Move-out history)                               │   │
│   │   │   └── Leave Processing (Checkout workflow)                              │   │
│   │   ├── Revenue & Expense (Income/Expense tracking)                           │   │
│   │   │   ├── Income Records                                                    │   │
│   │   │   ├── Expense Records (Fixed/Variable)                                  │   │
│   │   │   └── Break-even Analysis                                               │   │
│   │   ├── Fiscal Period Management                                              │   │
│   │   │   ├── Create/Close Periods                                              │   │
│   │   │   ├── Opening/Closing Balances                                          │   │
│   │   │   └── Period Reports Export                                             │   │
│   │   ├── Balance Sheet Items (Assets/Liabilities/Equity)                       │   │
│   │   └── System Settings                                                       │   │
│   │       ├── Company Information                                               │   │
│   │       ├── Currency & Timezone                                               │   │
│   │       └── Application Preferences                                           │   │
│   └─────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                      │
│   ┌─────────────────────────────────────────────────────────────────────────────┐   │
│   │                           SUPERVISOR ROLE                                    │   │
│   │   Manages customer rentals and payments for assigned apartments             │   │
│   │                                                                              │   │
│   │   ├── Dashboard (Supervisor-specific stats)                                 │   │
│   │   ├── Customer Management (CRUD customers)                                  │   │
│   │   ├── Rental Management (Lease agreements)                                  │   │
│   │   ├── Payment Management (Track rent payments)                              │   │
│   │   └── View Apartments (Read-only apartment list)                            │   │
│   └─────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                      │
│   ┌─────────────────────────────────────────────────────────────────────────────┐   │
│   │                            TENANT ROLE                                       │   │
│   │   Limited access for tenants to view their own information                  │   │
│   │                                                                              │   │
│   │   └── Dashboard (Personal rental information)                               │   │
│   └─────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Database Entity-Relationship Diagram (ERD)

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                   DATABASE ERD - AMS                                                 │
└─────────────────────────────────────────────────────────────────────────────────────────────────────┘

                                    ┌─────────────────────────────┐
                                    │          USERS              │
                                    ├─────────────────────────────┤
                                    │ PK id                       │
                                    │    name                     │
                                    │    email (unique)           │
                                    │    email_verified_at        │
                                    │    password                 │
                                    │    status                   │
                                    │    last_login_at            │
                                    │    remember_token           │
                                    │    timestamps               │
                                    └──────────┬──────────────────┘
                                               │
              ┌────────────────────────────────┼────────────────────────────────┐
              │                                │                                │
              │ 1                              │ 1                              │ 1
              ▼                                ▼                                ▼
┌─────────────────────────────┐  ┌─────────────────────────────┐  ┌─────────────────────────────┐
│        CUSTOMERS            │  │      ACTIVITY_LOGS          │  │    ROLE/PERMISSION          │
├─────────────────────────────┤  ├─────────────────────────────┤  │    (Spatie Package)         │
│ PK id                       │  │ PK id                       │  ├─────────────────────────────┤
│ FK user_id ─────────────────┼──│ FK user_id ─────────────────┼──│ roles                       │
│    name                     │  │    action                   │  │ permissions                 │
│    date_of_birth            │  │    target_type              │  │ model_has_roles             │
│    place_of_birth           │  │    target_id                │  │ model_has_permissions       │
│    contact_number           │  │    ip_address               │  │ role_has_permissions        │
│    id_photo                 │  │    timestamps               │  └─────────────────────────────┘
│    customer_photo           │  └─────────────────────────────┘
│    status                   │
│    timestamps               │
└──────────────┬──────────────┘
               │
               │ 1
               ▼
               │                    ┌─────────────────────────────┐
               │                    │          FLOORS             │
               │                    ├─────────────────────────────┤
               │                    │ PK id                       │
               │                    │    name                     │
               │                    │    timestamps               │
               │                    └──────────────┬──────────────┘
               │                                   │
               │                                   │ 1
               │                                   ▼
               │                    ┌─────────────────────────────┐
               │                    │        APARTMENTS           │
               │                    ├─────────────────────────────┤
               │                    │ PK id                       │
               │          ┌─────────│ FK floor_id                 │
               │          │         │ FK supervisor_id ───────────┼──────────────────┐
               │          │         │    room_number              │                  │
               │          │         │    apartment_number         │                  │
               │          │         │    monthly_rent             │                  │
               │          │         │    status                   │                  │
               │          │         │    timestamps               │                  │
               │          │         └──────────────┬──────────────┘                  │
               │          │                        │                                 │
               │          │         ┌──────────────┼──────────────┐                  │
               │          │         │ 1            │              │ 1                │
               │          │         ▼              │              ▼                  │
               │          │  ┌──────────────────┐  │  ┌─────────────────────────────┐│
               │          │  │     TENANTS      │  │  │         RENTALS             ││
               │          │  ├──────────────────┤  │  ├─────────────────────────────┤│
               │          │  │ PK id            │  │  │ PK id                       ││
               │          │  │ FK apartment_id ─┼──┘  │ FK customer_id ─────────────┼┘
               │          │  │    name          │     │ FK apartment_id ────────────┼─┐
               │          │  │    email (unique)│     │    start_date               │ │
               │          │  │    phone         │     │    end_date                 │ │
               │          │  │    address       │     │    rent_amount              │ │
               │          │  │    move_in_date  │     │    deposit                  │ │
               │          │  │    move_out_date │     │    status                   │ │
               │          │  │    status        │     │    timestamps               │ │
               │          │  │    notes         │     └──────────────┬──────────────┘ │
               │          │  │    photo_path    │                    │                │
               │          │  │    document_path │                    │ 1              │
               │          │  │    archived_at   │                    ▼                │
               │          │  │    leave_reason  │     ┌─────────────────────────────┐ │
               │          │  │ final_utility_   │     │         PAYMENTS            │ │
               │          │  │   charges        │     ├─────────────────────────────┤ │
               │          │  │ final_other_     │     │ PK id                       │ │
               │          │  │   charges        │     │ FK rental_id ───────────────┼─┘
               │          │  │ total_rent_paid  │     │    amount                   │
               │          │  │    invoice_notes │     │    payment_method           │
               │          │  │    timestamps    │     │    payment_status           │
               │          │  └──────────────────┘     │    transaction_reference    │
               │          │                           │    paid_at                  │
               │          │                           │    timestamps               │
               │          │                           └──────────────┬──────────────┘
               │          │                                          │
               │          │                                          │ 1
               │          │                                          ▼
               │          │                           ┌─────────────────────────────┐
               │          │                           │         ACCOUNTS            │
               │          │                           ├─────────────────────────────┤
               │          │                           │ PK id                       │
               │          │         ┌─────────────────│ FK fiscal_period_id         │
               │          │         │                 │ FK payment_id ──────────────┤
               │          │         │                 │ FK user_id ─────────────────┼────┐
               │          │         │                 │    account_type (enum)      │    │
               │          │         │                 │    category (enum)          │    │
               │          │         │                 │    cost_type (enum)         │    │
               │          │         │                 │    description              │    │
               │          │         │                 │    amount                   │    │
               │          │         │                 │    transaction_date         │    │
               │          │         │                 │    month                    │    │
               │          │         │                 │    year                     │    │
               │          │         │                 │    reference_number         │    │
               │          │         │                 │    notes                    │    │
               │          │         │                 │    timestamps               │    │
               │          │         │                 └─────────────────────────────┘    │
               │          │         │                                                    │
               │          │         │                                                    │
               │          │         │                                                    │
               │          │         ▼                                                    │
               │          │  ┌─────────────────────────────┐                            │
               │          │  │      FISCAL_PERIODS         │                            │
               │          │  ├─────────────────────────────┤                            │
               │          │  │ PK id                       │                            │
               │          │  │ FK created_by ──────────────┼────────────────────────────┤
               │          │  │ FK closed_by ───────────────┼────────────────────────────┤
               │          │  │    name                     │                            │
               │          │  │    opening_date             │                            │
               │          │  │    closing_date             │                            │
               │          │  │    opening_balance          │                            │
               │          │  │    closing_balance          │                            │
               │          │  │    status (enum)            │                            │
               │          │  │    is_current               │                            │
               │          │  │    notes                    │                            │
               │          │  │    closed_at                │                            │
               │          │  │    timestamps               │                            │
               │          │  └──────────────┬──────────────┘                            │
               │          │                 │                                            │
               │          │                 │ 1                                          │
               │          │                 ▼                                            │
               │          │  ┌─────────────────────────────┐                            │
               │          │  │   BALANCE_SHEET_ITEMS       │                            │
               │          │  ├─────────────────────────────┤                            │
               │          │  │ PK id                       │                            │
               │          │  │ FK fiscal_period_id ────────┤                            │
               │          │  │ FK user_id ─────────────────┼────────────────────────────┘
               │          │  │    item_type (enum)         │
               │          │  │    sub_type (enum)          │
               │          │  │    name                     │
               │          │  │    description              │
               │          │  │    amount                   │
               │          │  │    as_of_date               │
               │          │  │    reference_number         │
               │          │  │    notes                    │
               │          │  │    timestamps               │
               │          │  └─────────────────────────────┘
               │          │
               │          │
               │          │  ┌─────────────────────────────┐     ┌─────────────────────────────┐
               │          │  │         EXPENSES            │     │         SETTINGS            │
               │          │  ├─────────────────────────────┤     ├─────────────────────────────┤
               │          │  │ PK id                       │     │ PK id                       │
               │          │  │    type                     │     │    key (unique)             │
               │          │  │    category                 │     │    value                    │
               │          │  │    amount                   │     │    timestamps               │
               │          │  │    month                    │     └─────────────────────────────┘
               │          │  │    year                     │
               │          │  │    timestamps               │
               │          │  └─────────────────────────────┘
               │          │
               │          │
               │          └───────────────────────────────────────────────────────────────────────────┐
               │                                                                                       │
               └───────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Detailed Entity Descriptions

### Core Entities

| Entity | Description | Key Relationships |
|--------|-------------|-------------------|
| **User** | System users with authentication | Has roles (admin/supervisor/tenant), can have Customer profile |
| **Floor** | Building floors | Has many Apartments |
| **Apartment** | Individual rental units | Belongs to Floor, has Supervisor, has Tenants & Rentals |
| **Tenant** | Direct building tenants (Admin managed) | Belongs to Apartment, can be archived |
| **Customer** | Rental customers (Supervisor managed) | Belongs to User, has Rentals |
| **Rental** | Lease agreements | Belongs to Customer & Apartment, has Payments |
| **Payment** | Rent payments | Belongs to Rental, can link to Account |

### Financial Entities

| Entity | Description | Key Relationships |
|--------|-------------|-------------------|
| **Account** | Income/Expense transactions | Belongs to FiscalPeriod, optionally linked to Payment |
| **FiscalPeriod** | Accounting periods | Has many Accounts & BalanceSheetItems |
| **BalanceSheetItem** | Assets, Liabilities, Equity | Belongs to FiscalPeriod |
| **Expense** | Legacy expense tracking | Standalone (type, category, amount) |

### System Entities

| Entity | Description | Key Relationships |
|--------|-------------|-------------------|
| **Setting** | Key-value configuration store | Standalone |
| **ActivityLog** | User action audit trail | Belongs to User |

---

## Relationship Specifications

### One-to-Many Relationships

```
Floor           ──< Apartment         (1 Floor has many Apartments)
Apartment       ──< Tenant            (1 Apartment has many Tenants)
Apartment       ──< Rental            (1 Apartment has many Rentals)
Customer        ──< Rental            (1 Customer has many Rentals)
Rental          ──< Payment           (1 Rental has many Payments)
FiscalPeriod    ──< Account           (1 Fiscal Period has many Accounts)
FiscalPeriod    ──< BalanceSheetItem  (1 Fiscal Period has many Balance Sheet Items)
User            ──< ActivityLog       (1 User has many Activity Logs)
User            ──< Apartment         (1 Supervisor manages many Apartments)
```

### One-to-One Relationships

```
User            ──  Customer          (1 User has 1 Customer profile)
Payment         ──  Account           (1 Payment can link to 1 Account record)
```

### Belongs-To Relationships

```
Apartment       ──> Floor             (via floor_id)
Apartment       ──> User              (via supervisor_id)
Tenant          ──> Apartment         (via apartment_id)
Customer        ──> User              (via user_id)
Rental          ──> Customer          (via customer_id)
Rental          ──> Apartment         (via apartment_id)
Payment         ──> Rental            (via rental_id)
Account         ──> FiscalPeriod      (via fiscal_period_id)
Account         ──> Payment           (via payment_id)
Account         ──> User              (via user_id)
BalanceSheetItem──> FiscalPeriod      (via fiscal_period_id)
BalanceSheetItem──> User              (via user_id)
ActivityLog     ──> User              (via user_id)
```

---

## Enum Values Reference

### Account Types
- `income` - Revenue/income transactions
- `expense` - Cost/expense transactions

### Account Categories
**Income:** `rental_income`, `deposit`, `late_fee`, `other_income`  
**Fixed Costs:** `rent_building`, `insurance`, `property_tax`, `salary`, `loan_payment`, `depreciation`  
**Variable Costs:** `utilities`, `maintenance`, `cleaning`, `supplies`, `marketing`, `repairs`  
**Bank:** `bank_fee`, `bank_interest`, `bank_transfer`  
**Other:** `other_expense`

### Cost Types
- `fixed` - Fixed costs
- `variable` - Variable costs
- `bank` - Bank/Financial costs
- `income` - Income type

### Balance Sheet Item Types
- `asset` - Assets
- `liability` - Liabilities
- `equity` - Owner's equity

### Balance Sheet Sub-Types
**Assets:** `current_asset`, `fixed_asset`  
**Liabilities:** `current_liability`, `long_term_liability`  
**Equity:** `owner_equity`, `retained_earnings`

### Fiscal Period Status
- `draft` - Period in preparation
- `open` - Active period
- `closed` - Finalized period

### Tenant/Rental Status
- `active` - Currently active
- `inactive` - Temporarily inactive
- `moved_out` - Tenant has left

---

## Data Flow Diagram

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                            DATA FLOW - RENTAL PROCESS                             │
└──────────────────────────────────────────────────────────────────────────────────┘

  ┌─────────────┐         ┌─────────────┐         ┌─────────────┐
  │   CREATE    │         │   CREATE    │         │   CREATE    │
  │    FLOOR    │────────▶│  APARTMENT  │────────▶│   TENANT    │
  └─────────────┘         └─────────────┘         └─────────────┘
                                │                        │
                                │                        │
                                ▼                        ▼
                         ┌─────────────┐         ┌─────────────┐
                         │   ASSIGN    │         │   TRACK     │
                         │ SUPERVISOR  │         │  MOVE-IN    │
                         └─────────────┘         └─────────────┘
                                │                        │
                                ▼                        │
  ┌─────────────┐         ┌─────────────┐               │
  │   CREATE    │────────▶│   CREATE    │               │
  │  CUSTOMER   │         │   RENTAL    │◀──────────────┘
  └─────────────┘         └─────────────┘
                                │
                                ▼
                         ┌─────────────┐         ┌─────────────┐
                         │   COLLECT   │────────▶│   CREATE    │
                         │   PAYMENT   │         │   ACCOUNT   │
                         └─────────────┘         │   ENTRY     │
                                                 └─────────────┘
                                                        │
                                                        ▼
                                                 ┌─────────────┐
                                                 │   FISCAL    │
                                                 │   PERIOD    │
                                                 │   REPORT    │
                                                 └─────────────┘


┌──────────────────────────────────────────────────────────────────────────────────┐
│                          DATA FLOW - TENANT CHECKOUT                              │
└──────────────────────────────────────────────────────────────────────────────────┘

  ┌─────────────┐         ┌─────────────┐         ┌─────────────┐
  │   ACTIVE    │────────▶│   PROCESS   │────────▶│  CALCULATE  │
  │   TENANT    │         │    LEAVE    │         │FINAL CHARGES│
  └─────────────┘         └─────────────┘         └─────────────┘
                                                        │
                                                        ▼
                         ┌─────────────┐         ┌─────────────┐
                         │  GENERATE   │◀────────│   ARCHIVE   │
                         │   INVOICE   │         │   TENANT    │
                         └─────────────┘         └─────────────┘
```

---

*Last Updated: February 3, 2026*
