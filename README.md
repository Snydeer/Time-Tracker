# Agile Company Time Tracker

A time-clock web app for small businesses. Managers create employee accounts, set pay rates, post announcements and run pay reports. Employees clock in and out and see their timesheet and pay.

Built by team **Agile Company** for a Software Engineering class (Spring 2024): Nelson Ha, Avery Snyder, Esther Williams, Rasmey Oung and Abubakar Abdi. Original team repo: [Snydeer/Time-Tracker](https://github.com/Snydeer/Time-Tracker).

> ⚠️ **The live site is a demo.** The real app is PHP + MySQL and needs a server and a database, so the deployed version is a static recreation in [`demo/`](demo/). Every page works, logged in with sample data. The data lives only in your browser's `localStorage`, and the **Reset demo** button restores it.
>
> **The original PHP and SQL are unchanged** at the root of this repo, and you can still run them locally (see below).

### Demo accounts

| Role | Username | Password |
| --- | --- | --- |
| Manager | `demomanager` | `demo1234` |
| Employee | `jordanlee4821` | `jordanlee4821` |

Each login page also has a one-click "Log in with the demo account" button.

## Features

**Managers**
- Company sign-up (one manager account per company)
- Create employee accounts: the app generates a username (name + 4 random digits) and a temporary password
- View any employee's timesheet
- Set and update hourly pay rates
- Pay period reports: hours worked × pay rate for any date range
- Announcements with a confirm step and an expiration date

**Employees**
- Clock in / clock out
- Timesheet of every shift
- Dashboard with current wage, hours in the past 7 days and the latest announcement
- Pay report for any date range
- Change password

## Technology

- **Original app:** PHP 8 (PDO, sessions, `password_hash`), MySQL / MariaDB via XAMPP and phpMyAdmin, HTML, CSS/SCSS and a little JavaScript. Planned with an ERD, use cases, sequence diagrams and Figma wireframes (PDFs in the repo root).
- **Demo:** plain HTML, CSS and JavaScript (ES modules), no build step. `demo/js/store.js` recreates the five MySQL tables, including the trigger and the weekly cleanup event, in `localStorage`.

## Project structure

```
*.php                  Original pages (index, login, dashboard, timetracker, payroll, ...)
includes/              Form handlers (login.inc.php, signup.inc.php, create.inc.php, ...)
classes/               Dbh (PDO connection) -> models (Login, Signup, Create) -> controllers (*Contr)
sqlswe/                MySQL schema dump, trigger and event
app/css, app/js        Original styles and scripts
demo/                  Static demo (what gets deployed)
  about.html           About this project
  js/store.js          In-browser "database" + seed data
  js/app.js            Page logic (one handler per original PHP page)
  js/layout.js         Side nav / header (replaces header.php), demo bar, dark mode
vercel.json            Vercel config: serves only demo/
.github/workflows/     GitHub Pages deploy for demo/
```

## Deploying the demo

The demo is fully static, so it can go on either host. Only the `demo/` folder is published: the PHP files are never served.

**Vercel:** import the repo at vercel.com → New Project. `vercel.json` already sets the output directory to `demo`, so no settings need changing. (If Vercel asks, the framework preset is "Other".)

**GitHub Pages:** in the repo, go to Settings → Pages → Build and deployment → Source: **GitHub Actions**. The workflow in `.github/workflows/pages.yml` publishes `demo/` on every push to `main` that changes it, or you can run it by hand from the Actions tab.

**Locally:** ES modules don't load from `file://`, so serve the folder:

```bash
cd demo
python3 -m http.server 8000
# open http://localhost:8000
```

## Running the original PHP app locally

1. Install [XAMPP](https://www.apachefriends.org/) (Apache + MariaDB + PHP 8).
2. Copy this repo into `htdocs/` and start Apache and MySQL.
3. In phpMyAdmin, create a database named `OOPSWE` and import `sqlswe/oopswe.sql`. Optionally run `sqlswe/events.sql` too, after `SET GLOBAL event_scheduler = ON;`.
4. Open `http://localhost/Time-Tracker/`.

The connection settings in `connection.php` and `classes/dbh.classes.php` are XAMPP's local defaults (`root` with no password on `localhost`). They aren't secrets, but **don't** reuse them, or deploy this code as it is, on a public server. It was a learning project and isn't hardened for production (no CSRF protection, some pages don't check who is logged in, and some output isn't HTML-escaped).

## Database schema

```sql
CREATE TABLE users (            -- managers / business owners
  users_id INT AUTO_INCREMENT PRIMARY KEY,
  users_name TINYTEXT NOT NULL,
  users_email TINYTEXT NOT NULL,
  users_uid TINYTEXT NOT NULL,
  users_pwd LONGTEXT NOT NULL,  -- password_hash()
  users_company TINYTEXT NOT NULL
);

CREATE TABLE employee (
  employees_id INT AUTO_INCREMENT PRIMARY KEY,
  employees_name TINYTEXT NOT NULL,
  employees_email TINYTEXT NOT NULL,
  employees_uid TINYTEXT NOT NULL,
  employees_pwd LONGTEXT NOT NULL,
  employees_company VARCHAR(255)
);

CREATE TABLE payroll (
  employees_uid TINYTEXT NOT NULL,
  employees_company VARCHAR(255),
  pay_amount DECIMAL(10,2)
);

CREATE TABLE time_tracking (
  id INT AUTO_INCREMENT PRIMARY KEY,
  start_time TIMESTAMP NOT NULL,
  end_time TIMESTAMP NOT NULL,
  elapsed_time INT,             -- seconds
  username VARCHAR(255)
);

CREATE TABLE announcements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  announcement_text TEXT,
  date_posted TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expiration_date DATE
);

-- Every new employee automatically gets a payroll row
CREATE TRIGGER new_employee_trigger AFTER INSERT ON employee FOR EACH ROW
  INSERT INTO payroll (employees_uid, employees_company, pay_amount)
  VALUES (NEW.employees_uid, NEW.employees_company, 0);

-- Weekly cleanup of expired announcements
CREATE EVENT `Delete Announcements` ON SCHEDULE EVERY 1 WEEK
  DO DELETE FROM announcements WHERE expiration_date < CURDATE();
```
