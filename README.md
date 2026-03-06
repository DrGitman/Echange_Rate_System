# Exchange Rate System

A premium, high-performance currency exchange and trend analysis application built with **Laravel**, **Tailwind CSS**, and **.NET**.

## 🌟 Features

-   **Live Exchange Calculator**: Real-time conversions for over 160+ currencies worldwide.
-   **Dynamic Trend Graphs**: Visualize historical data across 1D, 1W, 1M, and 1Q timeframes.
-   **Intelligent Fallbacks**: Robust currency fetching system that uses multiple APIs (Open-ER, Frankfurter, and Fawazahmed0) to ensure data availability for minor currencies like NAD and BWP.
-   **Searchable Currencies**: Quick-filter system to find countries and currencies by name or code.
-   **User Profiles**: Customizable user accounts with avatar uploads and persistent dark mode settings.
-   **Secure Authentication**: Full login/signup system with advanced password security and visibility toggles.

## 🛠️ Technology Stack

-   **Frontend**: Tailwind CSS (with Glassmorphism), Vanilla JavaScript, Chart.js.
-   **Backend**: Laravel (PHP 8+).
-   **Data Processing**: C# (.NET Core) for high-performance currency fetching and API orchestration.
-   **Database**: PostgreSQL (configurable via `.env`).

## 🚀 Getting Started

### Prerequisites

-   **PHP 8.1+** & **Composer**
-   **.NET SDK 6.0+**
-   **Node.js & NPM** (optional, for asset compilation)
-   **PostgreSQL** (or any database supported by Laravel)

### Installation

1.  **Clone the Repository**:
    ```bash
    git clone <repository-url>
    cd Exchange_Rate_System
    ```

2.  **Laravel Setup**:
    ```bash
    composer install
    copy .env.example .env
    php artisan key:generate
    php artisan migrate
    ```

3.  **C# Tool Setup**:
    The system uses a custom C# tool located in `tools/CurrencyFetcher`. Ensure you have the .NET SDK installed. The Laravel app calls this tool automatically via `dotnet run`.

4.  **Run the Application**:
    ```bash
    php artisan serve
    ```
    Access the app at `http://127.0.0.1:8000`.

## 📁 Project Structure

-   `app/Http/Controllers`: Backend logic for rates and profile management.
-   `resources/views`: Blade templates for the UI.
-   `tools/CurrencyFetcher`: .NET Core project for API data retrieval.
-   `public/js`: Frontend interactivity scripts.
-   `resources/data`: Country and currency definitions.

## 📄 License

This project is for personal use. All rights reserved.
