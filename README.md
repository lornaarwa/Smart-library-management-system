# Smart Library Management System (SmartLib)

SmartLib is a production-grade, full-stack library management system featuring a **Laravel REST API** backend and a **React SPA** frontend. The platform handles digital/physical catalog searches (OPAC), barcode circulation, custom borrowing limits, Safaricom M-Pesa Daraja fine payments, and features an OpenAI-powered AI Assistant Chatbot.

---

## 🚀 Key Features

- **Online Public Access Catalog (OPAC)**: Fully searchable public book browser with filterable attributes (Genre, Author, ISBN).
- **Interactive Dashboards**:
  - **Admin**: System metrics, PostgreSQL database health status, and user suspension controls.
  - **Librarian**: Barcode scanner checkout desk, return handling, manual overrides, and borrowing limits configurator.
  - **Member**: Personal active checkouts, hold queue tracking, overdue alerts, and fine statements.
- **M-Pesa Daraja STK Push Integration**: Fast, automated payment of outstanding library fines directly through Safaricom API.
- **SmartLib AI Librarian**: Context-aware floating assistant chatbot providing automated book suggestions.
- **Complete Middleware Stack**: 14 custom middlewares monitoring role routing, token budgets, borrow limits, and API throttling.

---

## 🛠️ Tech Stack & Architecture

### Backend (Laravel 12 / PHP 8.2)
- **ORM**: Eloquent
- **Database**: PostgreSQL (or SQLite local fallback)
- **Auth**: JWT (JSON Web Token) encoding/decoding and blacklisting
- **Object Storage**: Cover images and digital PDF book files

### Frontend (React 18 / Vite 7)
- **Styling**: Vanilla CSS and custom Tailwind
- **Icons**: Lucide React
- **API Client**: Axios

---

## 📂 Core Directory Structure

```text
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Auth, Inventory, Loan, Fine, AI Chatbot Controllers
│   │   └── Middleware/           # 14 custom middlewares (IP, JWT, Banned, Fines limit, etc.)
│   ├── Models/                   # 10 DB Models (Book, BookCopy, Member, Reservation, etc.)
│   ├── Providers/                # 6 Custom Service Providers (Daraja, OpenAI Chat, Search Engine)
│   └── Services/                 # 9 Domain Services (Token Bucket, Catalog Search, Queue sequencing)
├── database/
│   ├── migrations/               # Database tables schema definition
│   └── seeders/                  # Test seeder with dummy catalog & student records
├── resources/
│   ├── css/                      # Custom Plus Jakarta Sans styling
│   ├── js/                       # React SPA src directory
│   │   ├── components/           # Navbar, Footer, BookCard, DarajaModal, ChatbotWidget
│   │   ├── context/              # AuthStateContext and session persistence
│   │   └── pages/                # PublicCatalog, Member, Librarian, Admin, Login Dashboards
│   └── views/app.blade.php       # Single blade template rendering the React app
├── routes/
│   ├── api.php                   # All REST API endpoints (V1)
│   └── web.php                   # Single Page App layout fallback routing
```

---

## 🔒 Custom Middleware Stack (14 Middlewares)

| Middleware | Description |
| :--- | :--- |
| **`EnsureIsLibrarian`** | Restricts Librarian/Admin dashboards to authorized personnel only. |
| **`EnsureHasAccount`** | Blocks unauthenticated users from dashboard resources. |
| **`ValidateBorrowLimit`** | Enforces maximum active checkouts based on membership tier. |
| **`CheckBookAvailability`** | Restricts checkouts for administratively locked books or out-of-stock items. |
| **`CheckReservationAvailability`** | Prevents duplicate holds on the same title by a member. |
| **`JwtTokenValidation`** | Decodes and validates bearer session JWT signatures. |
| **`ThrottleRequests`** | Limits API request frequency to prevent DDoS (429 handling). |
| **`IpRateLimiter`** | Extra IP-based traffic limiter active in production. |
| **`Cors`** | Enforces allowable API cross-origin requests. |
| **`TrustProxies`** | Assures trusted reverse proxies like Cloudflare. |
| **`ApiGatewayProxy`** | Downstream gateway microservice header router. |
| **`CheckBannedStatus`** | Restricts suspended library accounts from logging in. |
| **`CheckFineAmount`** | Restricts checkouts if outstanding fines exceed KES limit. |
| **`ChatbotCostLimiter`** | Restricts daily AI token usage per user to protect API quotas. |

---

## ⚡ Setup & Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm

### Backend Configuration
1. Clone the repository and install dependencies:
   ```bash
   composer install
   ```
2. Copy environment configuration:
   ```bash
   cp .env.example .env
   ```
3. Set up the database:
   ```bash
   php artisan migrate:fresh --seed
   ```

### Frontend Configuration
1. Install Node modules:
   ```bash
   npm install --legacy-peer-deps
   ```
2. Build assets for production:
   ```bash
   npm run build
   ```
3. Start the Vite development server:
   ```bash
   npm run dev
   ```
