# 💍 Matrimony Website

A web-based matrimony platform that helps members discover compatible profiles, express interest, and communicate with potential life partners. It includes user authentication, profile management, document verification, profile search, member chat, and administrative controls.

## ✨ Features

### 👤 Member Features
- User registration and login with OTP functionality.
- Member profile management.
- Document uploads.
- Profile search and matching.
- Send interest to other members.
- Member-to-member chat.
- Privacy and account management.

### 🛡️ Admin Features
- Admin dashboard and navigation.
- Profile review and user management.
- Document verification.
- Safety reports and investigation.
- Communication moderation.
- Platform management and monitoring.
- Payments and donations management.

## 🏗️ Project Architecture

The application is divided into three major areas:

- **Public & Identity:** Public entry, registration, login, and OTP authentication.
- **Member Journey:** Profiles, document uploads, search, matching, chat, and privacy.
- **Admin Operations:** User management, document verification, safety, reports, and platform administration.

The shared runtime manages sessions and access, while MySQL stores application data.

### 📊 Interactive Repository Diagram

Explore the project structure and file relationships through GitDiagram.

[![Architecture diagram of tejashreejadhav16/matrimony](https://gitdiagram.com/tejashreejadhav16/matrimony/diagram.png)](https://gitdiagram.com/tejashreejadhav16/matrimony?utm_source=readme&utm_medium=picture)
## 🛠️ Technology Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Local Environment:** XAMPP
- **Code Editor:** Visual Studio Code

*Verify the technologies against the actual source code before publishing.*

## 📂 Main Files

| File | Purpose |
|---|---|
| `index.php` | Public entry page |
| `register.php` | User registration |
| `login-process.php` | Login and OTP processing |
| `sidebar.php` | Navigation component |
| `search.php` | Profile search |
| `chat.php` | Member communication |
| `send-interest.php` | Interest and matching |
| `session.php` | Session and access management |
| `config.php` | Database configuration |

*The filenames and descriptions above are based on the generated repository diagram. Confirm them against your actual repository.*

## ⚙️ Installation & Setup

### Prerequisites

- Git
- XAMPP with Apache, PHP, and MySQL
- Visual Studio Code
- A web browser

### Step 1: Clone the Repository

Open the VS Code terminal and run:

```bash
git clone YOUR_GITHUB_REPOSITORY_URL
```

Navigate into the project directory:

```bash
cd matrimony
```

If you have already downloaded the repository, open its folder in VS Code instead.

### Step 2: Set Up XAMPP

Move or copy the project folder into:

```text
C:\xampp\htdocs\matrimony
```

Open the XAMPP Control Panel and start:

- Apache
- MySQL

### Step 3: Configure the Database

1. Open [phpMyAdmin](http://localhost/phpmyadmin).
2. Create a MySQL database for the application.
3. Import the project's `.sql` database file, if available.
4. Open `config.php` in VS Code.
5. Configure the database host, database name, username, and password according to your local MySQL setup.

**Important:** Do not commit real database passwords or other secrets to GitHub.

### Step 4: Run the Application

Open your browser and visit:

```text
http://localhost/matrimony/
```

The website should load if Apache is running, the project is in the correct directory, and the database configuration is valid.

## ▶️ Useful Commands

Run these commands in the VS Code terminal when needed.

**Check Git installation:**

```bash
git --version
```

**Check PHP installation:**

```bash
php -v
```

**Open the project in VS Code:**

```bash
code .
```

**Clone the project:**

```bash
git clone YOUR_GITHUB_REPOSITORY_URL
```

**Navigate to the project folder:**

```bash
cd matrimony
```

**Optional: Start PHP's built-in development server**

If PHP is installed and available in your system PATH, run this command from the project directory:

```bash
php -S localhost:8000
```

Then visit:

```text
http://localhost:8000
```

For the standard XAMPP setup, use `http://localhost/matrimony/` instead. Start MySQL separately when the application requires database access.

## 🔐 Security Considerations

- Hash passwords securely.
- Validate user input and use prepared SQL statements.
- Validate document uploads and restrict allowed file types.
- Protect authenticated pages and administrator routes.
- Keep database credentials and secrets out of public repositories.

## 🚀 Future Enhancements

- Advanced compatibility matching.
- Email and SMS notifications.
- Enhanced profile filters.
- Improved mobile responsiveness.
- Additional privacy and reporting features.



---

*Built to simplify profile discovery and communication through a structured matrimony platform.*
