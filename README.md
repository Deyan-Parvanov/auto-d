# Welcome to AutoD

## Overview
**AutoD** is a comprehensive car selling and buying application.

### Key Features:
- **Listings:** Browse listings on the main `/listing` page and apply filters based on your specific requirements.
- **Private Listings Page:** Accessible via your username (located next to the "New Listing" button), this section allows you to:
  - View and filter your personal listings.
  - Preview, edit, soft delete, and restore listings.
  - Upload images for your listings.
  - View offers made for your listings and accept them.
- **Dynamic Loan Calculation:** When making offers, dynamically calculate key loan details such as:
  - Interest rate
  - Principal amount
  - Monthly payment
  - Loan duration
- **Notifications:** Receive instant notifications for offers made on your listings:
  - Notifications are accessible via the 🔔 icon.
  - Clicking a notification redirects you to a dedicated page to view all offers and mark them as "read".

---
### Prerequisites:
Ensure you have **Docker** and **VSCode** installed.

## Installation
AutoD is fully dockerized, ensuring a seamless setup process. Follow these steps to run the application locally:

### Steps:
1. Clone the repository:
   - git clone <repo-link>
   - cd <repo-directory>
   - git pull
2. Build containers:
   - docker-compose up -d
3. Install packages:
   - composer install
   - npm install
4. Run migrations and seed the databse:
   - docker-compose exec php php artisan migrate:refresh --seed
    - **Note:** This command will create the database and will seed all tables with data - 10 car listings for admin and 10 car listings for regular user.
    - **There will be two available users:**
        - Admin: email: - test@example.com, password - password
        - Regular user: email: - test2@example.com, password - password
5. Run the development server:
   - npm run dev
6. Open the app:
   - http://localhost

### Alternative approach
If you prefer not to use Docker, ensure the following are installed:
  - PHP
  - Composer
  - XAMPP
  - Node.js
  - Docker (for the MySQL container) or MySQL locally.

### Architecture

**Tech Stack**
  - Database: MySQL
  - Backend: PHP (Laravel)
  - Frontend: Vue.js

**Key Details**
  - The application runs on a Laravel skeleton with Vue.js integrated into a single Blade file.
  - All Vue.js code resides in the /resources/js directory.
  - AJAX Requests: Managed using Axios.
  - Routing: Handled by Vue Router.
  - State Management: Powered by Pinia.
  - Styling: Implemented using Tailwind CSS.
  - Form Validation: Utilizes Vuelidate.
  - Utility Libraries: Includes Lodash for debounce functionality.
  - Frontend Development Server: Managed by Vite.
  - Authentication: Secure communication between the Laravel API and the client is facilitated by Sanctum bearer tokens.
