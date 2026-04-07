# Project Analysis Report: Insaf Backend

## 1. Project Overview

**Project Name:** Education Consultancy Backend (`insaf_backend`)
**Purpose:** A CRM and management system for an education consultancy firm, handling student leads, applications to universities, financial tracking (commissions, salaries), and reporting.
**Base Framework:** Laravel 12.0
**PHP Requirement:** ^8.2

---

## 2. Technical Stack & Key Dependencies

- **Core Framework:** Laravel 12
- **Dashboard UI:** `hasinhayder/tyro-dashboard`
- **PDF Generation:** `barryvdh/laravel-dompdf`
- **Excel Export/Import:** `maatwebsite/excel`
- **API Authentication:** Laravel Sanctum
- **Build Tool:** Vite

---

## 3. Application Architecture

The project follows a standard Laravel structure with some organizational enhancements:

### Key Directories:

- **`app/Http/Controllers/Admin/`**: Contains administrative controllers for all major modules (Applications, Leads, Universities, etc.).
- **`app/Models/`**: Domain models representing the core entities.
- **`app/Services/`**: Contains specialized business logic like `CommissionService` and `CurrencyService`.
- **`app/Helpers/`**: Includes a `settings.php` helper for global configuration.
- **`app/Notifications/`**: Handles automated alerts for new leads and applications.

---

## 4. Core Modules & Entities

### **A. Lead Management**

- **Lead Model**: Captures prospective student data (name, email, phone, current education, preferred country).
- **Functionality**: Tracking lead status, follow-ups, and conversion to students.

### **B. Application & University Management**

- **University & Course Models**: Stores data on partner institutions and their programs.
- **Application Model**: Tracks student applications to universities, including `tuition_fee`, `total_fee`, and status updates.
- **Automated ID Generation**: Applications follow a specific format (e.g., `APP-2026-00001`).

### **C. Finance & Commissions**

- **Commission & Salary Models**: Tracks earnings from universities and staff payroll.
- **Office Transactions**: Handles office accounts and day-to-day expenses.
- **Currency Support**: Includes currency conversion and tracking.

### **D. Accounts & Financial Management (Planned)**

- **Chart of Accounts (COA)**: Hierarchical account management for professional bookkeeping.
- **Double-Entry Ledger**: Automated journaling for all financial events (Payments, Expenses, Salaries).
- **Invoicing**: Professional billing for students and partner universities with VAT/Tax support.
- **Financial Reporting**: Balance Sheet, Profit & Loss, and Bank Reconciliation.

### **E. Digital Marketing & Campaign Management**

- **Campaign Tracking (Normalized Architecture - 3 Tables)**: A robust three-table structure for tracking social media campaigns and their independent media assets. This approach is chosen for its scalability and auditability.
- **Media Asset Management**: Dedicated tables for tracking multiple videos (edited, upload, ready) and success posters per campaign.
- **Automation**: Potential integration for tracking performance metrics and costs over time.

---

## 5. Routes & Endpoints

- **Web Routes**: Primarily for the admin dashboard and template management.
- **API Routes**: Prepared for potential mobile app or frontend integration (though currently largely dashboard-driven).
- **Authentication**: Redirects to a login page for protected resources.

---

## 6. Codebase Quality & Observations

- **Consistency**: Use of custom `booted` methods in models for automated field generation shows good use of Laravel's lifecycle events.
- **Separation of Concerns**: Moving complex logic like commission calculations into dedicated services is a strong design pattern.
- **Reporting**: Inclusion of PDF and Excel libraries suggests a focus on reporting and data portability.

---

## 7. Recommended Next Steps

1. **Testing**: Implement feature tests for critical paths like `Lead to Application` conversion.
2. **Type Safety**: Consider adding parameter and return type hints where missing in controllers.
3. **Optimized Queries**: Review `Application` and `Lead` index views for potential N+1 query issues.

---

## 8. Database Schema

This section provides a detailed overview of the database structure, organized by functional modules.

### **A. Core & User Management**

Handles authentication, user roles, and system settings.

| Table                        | Columns                                                                                                                                                                                                                              | Description                                   |
| :--------------------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :-------------------------------------------- |
| **`users`**                  | `id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `basic_salary`, `bank_name`, `account_number`, `branch_name`, `account_holder_name`, `routing_number`, `commission_percentage`, `created_at`, `updated_at` | Core user data with payroll and bank details. |
| **`personal_access_tokens`** | `id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`                                                                                                       | API authentication tokens (Laravel Sanctum).  |
| **`settings`**               | `id`, `key`, `value`, `group`, `created_at`, `updated_at`                                                                                                                                                                            | System-wide configuration settings.           |

### **B. Lead & Student Management**

Tracks prospective leads and converted students.

| Table          | Columns                                                                                                                                 | Description                                                |
| :------------- | :-------------------------------------------------------------------------------------------------------------------------------------- | :--------------------------------------------------------- |
| **`leads`**    | `id`, `name`, `email`, `phone`, `current_education`, `preferred_country`, `source`, `status`, `assigned_to`, `created_at`, `updated_at` | Prospective student inquiries and their tracking status.   |
| **`students`** | `id`, `lead_id`, `student_id`, `passport_number`, `date_of_birth`, `address`, `created_at`, `updated_at`                                | Converted students with additional personal documentation. |

### **C. University & Admissions**

Manages partner institutions, courses, and application workflows.

| Table                | Columns                                                                                                                                             | Description                                           |
| :------------------- | :-------------------------------------------------------------------------------------------------------------------------------------------------- | :---------------------------------------------------- |
| **`countries`**      | `id`, `name`, `code`, `is_active`, `created_at`, `updated_at`                                                                                       | List of countries for universities and leads.         |
| **`universities`**   | `id`, `country_id`, `name`, `website`, `logo`, `is_active`, `created_at`, `updated_at`                                                              | Partner educational institutions.                     |
| **`courses`**        | `id`, `university_id`, `name`, `level`, `duration`, `currency_id`, `created_at`, `updated_at`                                                       | Courses offered by universities.                      |
| **`course_intakes`** | `id`, `course_id`, `intake_month`, `intake_year`, `deadline`, `created_at`, `updated_at`                                                            | Specific enrollment periods for courses.              |
| **`applications`**   | `id`, `student_id`, `course_id`, `intake_id`, `application_number`, `status`, `tuition_fee`, `total_fee`, `currency_id`, `created_at`, `updated_at` | Student applications to specific courses and intakes. |

### **D. Finance & Accounting**

Handles payments, expenses, payroll, and commissions.

| Table                     | Columns                                                                                                                             | Description                                             |
| :------------------------ | :---------------------------------------------------------------------------------------------------------------------------------- | :------------------------------------------------------ |
| **`currencies`**          | `id`, `name`, `code`, `symbol`, `exchange_rate`, `is_default`, `created_at`, `updated_at`                                           | Multi-currency support with exchange rates.             |
| **`office_accounts`**     | `id`, `account_name`, `account_number`, `bank_name`, `opening_balance`, `balance`, `created_at`, `updated_at`                       | Bank and cash accounts for office transactions.         |
| **`office_transactions`** | `id`, `account_id`, `amount`, `type` (income/expense), `description`, `date`, `created_at`, `updated_at`                            | Ledger for all office-related money movements.          |
| **`payments`**            | `id`, `student_id`, `application_id`, `account_id`, `amount`, `payment_date`, `payment_method`, `notes`, `created_at`, `updated_at` | Student fee payments and deposits.                      |
| **`expenses`**            | `id`, `category_id`, `account_id`, `amount`, `date`, `description`, `salary_id`, `created_at`, `updated_at`                         | General office and operational expenses.                |
| **`finance_categories`**  | `id`, `name`, `type`, `created_at`, `updated_at`                                                                                    | Categorization for expenses and income.                 |
| **`salaries`**            | `id`, `user_id`, `amount`, `month`, `year`, `payment_date`, `is_template`, `created_at`, `updated_at`                               | Staff payroll and salary disbursements.                 |
| **`commissions`**         | `id`, `application_id`, `user_id`, `amount`, `percentage`, `status`, `created_at`, `updated_at`                                     | Commissions earned by staff on successful applications. |
| **`budgets`**             | `id`, `category_id`, `amount`, `period`, `created_at`, `updated_at`                                                                 | Budget planning for different expense categories.       |

#### **Accounts Module: Database Migration & Consolidation (Planned)**

This section details the structural evolution from a simple ledger to a professional double-entry system.

##### **A. New Tables to be ADDED**

| Table                      | Columns                                                                                                                | Description                                     |
| :------------------------- | :--------------------------------------------------------------------------------------------------------------------- | :---------------------------------------------- |
| **`chart_of_accounts`**    | `id`, `parent_id`, `code`, `name`, `type`, `is_active`, `is_default`, `created_at`, `updated_at`                       | Hierarchical financial categorization.          |
| **`journal_entries`**      | `id`, `date`, `reference_number`, `note`, `status`, `created_by`, `created_at`, `updated_at`                           | Balanced transaction header.                    |
| **`journal_entry_items`**  | `id`, `journal_entry_id`, `chart_of_account_id`, `debit`, `credit`, `description`, `created_at`, `updated_at`          | Individual Debit/Credit lines.                  |
| **`taxes`**                | `id`, `name`, `rate`, `is_active`, `created_at`, `updated_at`                                                          | VAT and Sales Tax configurations.               |
| **`invoices`**             | `id`, `student/univ_id`, `invoice_num`, `date`, `due_date`, `currency_id`, `total_amount`, `status`, `created_at`      | Billing for students and universities.          |
| **`invoice_items`**        | `id`, `invoice_id`, `coa_id`, `description`, `quantity`, `unit_price`, `subtotal`, `tax_amount`, `total`, `created_at` | Detailed line items for services.               |
| **`bank_reconciliations`** | `id`, `account_id`, `statement_date`, `statement_balance`, `system_balance`, `status`, `created_at`                    | Verifying system balances against bank records. |

### **8.F Marketing & Assets (Normalized Schema)**

| Table                     | Columns                                                                                                                                                                        | Description                                           |
| :------------------------ | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :---------------------------------------------------- |
| **`marketing_campaigns`** | `id`, `name`, `boosting_status` (on/off), `created_at`, `updated_at`                                                                                                           | Parent campaign table for overall tracking.           |
| **`marketing_videos`**    | `id`, `campaign_id` (FK), `status` (edited, upload, not_edited, ready), `created_at`, `updated_at`                                                                             | Dedicated table for multiple videos per campaign.     |
| **`marketing_posters`**   | `id`, `campaign_id` (FK), `status` (ready, not_ready, uploaded), `created_at`, `updated_at`                                                                                    | Dedicated table for multiple posters per campaign.    |

> [!NOTE]
> **Architecture Decision**: We have chosen a **3 Table (Normalized)** approach for the Marketing module. This allows for professional-grade scalability, enabling the team to manage multiple videos and posters for each individual campaign.

##### **B. Existing Tables to be MODIFIED**

| Table                 | Change                               | Reason                                                       |
| :-------------------- | :----------------------------------- | :----------------------------------------------------------- |
| **`payments`**        | Add `invoice_id`, `journal_entry_id` | To link student receipts to invoices and the general ledger. |
| **`expenses`**        | Add `journal_entry_id`               | To link office costs to the general ledger.                  |
| **`office_accounts`** | Add `chart_of_account_id`            | To sync cash/bank balances with the bookkeeping system.      |
| **`salaries`**        | Add `journal_entry_id`               | To record payroll expenses in the ledger.                    |

##### **C. Tables to be DEPRECATED (Moved)**

| Table                     | Status            | Migration Action                                               |
| :------------------------ | :---------------- | :------------------------------------------------------------- |
| **`finance_categories`**  | ❌ **Deprecated** | All categories will be migrated into `chart_of_accounts`.      |
| **`office_transactions`** | ❌ **Deprecated** | All records will be converted into balanced `journal_entries`. |

### **E. System & Notifications**

Background processing and system alerts.

| Table               | Columns                                                                                         | Description                     |
| :------------------ | :---------------------------------------------------------------------------------------------- | :------------------------------ |
| **`notifications`** | `id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at` | System notifications for users. |
| **`jobs`**          | `id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`               | Queue for background tasks.     |
| **`cache`**         | `key`, `value`, `expiration`                                                                    | System cache storage.           |

---

## 9. Accounts Module (Proposed: Double-Entry System)

To transition from simple transaction tracking to a professional-grade financial management system, the following "Accounts" module is proposed.

### **Core Accounting Features**

1.  **Chart of Accounts (COA)**: A hierarchical structure (Assets, Liabilities, Equity, Revenue, Expenses) with numeric account codes (e.g., `1000 - Cash`).
2.  **Double-Entry Journaling**: All manual and automated transactions will record matching Debits and Credits, ensuring the **Trial Balance** always nets to zero.
3.  **General Ledger**: A centralized record of all financial activity per account, allowing for deep auditing.
4.  **Student & University Invoicing**:
    - Professional PDF invoices for tuition fees, registration, and consultancy services.
    - Commission claims to universities tracked as "Accounts Receivable."
5.  **VAT & Tax Manager**: Automated calculation and tracking of VAT/Sales Tax for government compliance.

### **Financial Reports**

- **Balance Sheet**: A real-time snapshot of the company's financial health (What you OWN vs. What you OWE).
- **Profit & Loss (Income Statement)**: Detailed breakdown of Revenue vs. Expenses over any custom period.
- **Cash Flow Statement**: Tracking how cash enters and leaves the business.
- **Budget vs. Actual**: Comparing planned budgets with real spending for better oversight.

---

## 10. Technical Architecture & Next Steps

- **Double-Entry Logic**: Implementing an `AccountService` to handle automated journaling for `Payments`, `Expenses`, and `Salaries`.
- **Database Migration**: Deploying the 6 new tables (`chart_of_accounts`, `journal_entries`, `invoices`, etc.).
- **UI Integration**: Adding a dedicated "Accounts" dashboard to the Tyro sidebar.
