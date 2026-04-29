# 💼 Work Manager - Laravel Task & Financial Management System

A complete work and financial management web application built with Laravel for tracking deposits, expenses, tasks, and generating comprehensive reports.

## 📋 Features

### 💵 Money Management
- **Deposit Tracking**: Record who deposited money, how much, to whom, and for what purpose
- **Expense Tracking**: Track who spent money, where, at what location, and to whom it was paid
- **Real-time Balance**: Automatically calculates current balance (Total Deposits - Total Expenses)

### 📝 Task Management
- **Task CRUD**: Create, Read, Update, Delete tasks
- **Priority Levels**: Low, Medium, High, Urgent
- **Status Tracking**: Pending, In Progress, Completed, Cancelled
- **Cost Tracking**: Estimated and actual cost for each task
- **Due Dates**: Set deadlines with overdue highlighting
- **Quick Status Update**: AJAX-based status changes without page reload

### 🏷️ Category Management
- **Custom Categories**: Create categories for expenses and tasks
- **Visual Customization**: Choose colors and icons for each category
- **Active/Inactive**: Toggle category status
- **Usage Tracking**: See how many expenses/tasks use each category

### 📊 Comprehensive Reports
- **Person Wise Report**: Per person deposits, expenses, minimum required deposit, due/excess status
- **Deposit Report**: Filter by date, depositor with summary statistics
- **Expense Report**: Filter by date, category, spender with breakdowns by category, person, and location
- **Due Report**: Shows who needs to deposit more (based on Total Expenses ÷ 3 formula)
- **Cash in Hand Report**: Current balance per person (Received - Spent)
- **Received By Report**: Who received how much money with statistics
- **Monthly Report**: Month-by-month income and expense analysis with year selection

### 🔐 User Authentication
- **Multi-user Support**: Each user sees only their own data
- **Registration & Login**: Custom authentication system
- **Secure Access**: Middleware protection for all routes

## 🚀 Installation

### Prerequisites
- PHP 8.0 or higher
- Composer
- MySQL/MariaDB
- Node.js & NPM (optional, for asset compilation)

### Step 1: Clone the Repository
```bash
git clone <your-repository-url>
cd work-manager