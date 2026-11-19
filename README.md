# SkillSwap
Peer-to-peer skill exchange for students. Plain HTML/CSS/JS + PHP (PDO) + MySQL.

## Run on XAMPP (step by step)
1. Install XAMPP, open the **XAMPP Control Panel**.
2. Click **Start** next to **Apache** and **MySQL** (both turn green).
3. Copy the whole `skillswap` folder to `C:/xampp/htdocs/skillswap`.
4. Open http://localhost/phpmyadmin.
5. Click the **Import** tab, choose `backend/database/schema.sql`, click **Go** (creates the `skillswap` database and tables).
6. Click the `skillswap` database, **Import** again, choose `backend/database/seed.sql`, click **Go**.
7. Open http://localhost/skillswap/frontend/index.html
8. Log in. Every demo account's password is `password`:
   - Admin: admin@skillswap.test
   - Students: amina@skillswap.test, karim@skillswap.test, nadia@skillswap.test

DB settings (host localhost, user root, empty password, db skillswap) live in `backend/config/db.php`.

## Fonts
Put `Fraunces.woff2` and `Nunito.woff2` (variable fonts from fonts.google.com, converted to woff2) in `frontend/assets/fonts/`. Without them the site falls back to Georgia / system-ui.

## Troubleshooting
- Blank/"Database connection failed": MySQL not started, or schema.sql not imported.
- Redirected to login on every page: open the site through `http://localhost/...`, not by double-clicking the file.
- Port 80/3306 busy: stop Skype/IIS or change the port in XAMPP config.
