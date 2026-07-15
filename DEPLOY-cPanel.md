# Sparkman Solutions — cPanel Deployment Guide (no terminal needed)

This app is a **Laravel 10** site. It takes **live Authorize.Net payments** and stores
client PII, so follow every step — especially the security ones at the end.

Everything here is done through cPanel's **File Manager**, **MySQL Databases**, and
**phpMyAdmin** — no SSH/terminal required.

---

## 0. Before you start — pick your PHP version
In cPanel → **MultiPHP Manager**, set your domain to **PHP 8.2 or 8.3**.

**This is not optional.** `vendor/composer/platform_check.php` hard-requires
`PHP >= 8.2.0` and will fatal on every request under 8.0/8.1 with:
`"Composer detected issues in your platform: Your Composer dependencies require a PHP version >= 8.2.0"`.
If you use the `AddHandler` line in the root `.htaccess`, it must say `ea-php83`
(or `ea-php82`) — **never `ea-php81`**.

---

## 1. Create the database (phpMyAdmin import)
Already done for this deploy:

| | Value |
|---|---|
| Database | `fundmen1_jay` |
| DB user | `fundmen1_jayuser` |
| Host | `localhost` |

1. cPanel → **MySQL® Databases** → confirm **`fundmen1_jayuser` is added to `fundmen1_jay`
   with ALL PRIVILEGES** (creating the user is not enough — it must be *assigned*).
2. cPanel → **phpMyAdmin** → select **`fundmen1_jay`** on the left
3. Open the **Import** tab → choose **`database/sparkman_mysql_schema.sql`** → **Go**
4. You should see 10 tables created (clients, leads, payments, subscriptions, webhook_events, etc.)

> The admin login is **not** in the database — it lives in `.env` (step 4).

---

## 2. Upload the application files
Upload the **entire project** to your account, but **NOT inside `public_html`**.
Recommended layout:

```
/home/cpuser/
├── sparkman/            ← the whole Laravel app goes here
│   ├── app/  bootstrap/  config/  database/  resources/  routes/
│   ├── storage/  vendor/   ← vendor MUST be uploaded (no composer on cPanel)
│   └── .env
└── public_html/         ← web root (see step 3)
```

- Zip the project locally, upload the zip via **File Manager**, then **Extract** (much faster than uploading files one by one).
- **Do upload the `vendor/` folder** — there's no terminal to run `composer install`.
- **Do NOT upload** `database/database.sqlite` (production uses MySQL) or `node_modules`.

---

## 3. Point the web root at `/public`
Laravel must serve from its `public/` folder only. Two options:

**Option A (best) — set the Document Root**
cPanel → **Domains** → manage your domain → set **Document Root** to
`/home/cpuser/sparkman/public`. Done.

**Option B — if you can't change the Document Root**
Upload the **whole app directly into `public_html/`** (so you have
`public_html/app`, `public_html/public`, `public_html/.env`, …).

Two `.htaccess` files handle the rest — **both are already in this project**:

| File | Goes to | What it does |
|------|---------|--------------|
| `.htaccess` (project root) | `public_html/.htaccess` | Rewrites every request into `public/`, blocks direct access to `.env` / source / `.sql` / logs, sets the PHP handler, and lets `/.well-known/` through for SSL |
| `public/.htaccess` | `public_html/public/.htaccess` | Laravel's front controller (sends requests to `index.php`) |

Nothing to edit — just make sure **both files uploaded** (File Manager hides dotfiles
by default: enable **Settings → Show Hidden Files**).

> ⚠️ The PHP handler line in the root `.htaccess` is set to **`ea-php83`**. It must be
> **8.2 or newer** — this app will not boot on `ea-php81`/`ea-php80`. If your server
> doesn't have 8.3, change it to `ea-php82`, or set the version in **MultiPHP Manager**
> and delete that block entirely.

> 🔒 Option B puts app files inside the web root. The root `.htaccess` denies them, but
> Option A (Document Root → `/public`) is still the safer choice when available.

---

## 4. Create the `.env` file
**Nothing to fill in — it's ready.** Just rename **`.env.production` → `.env`** on the server.

It already contains, verified:
- `APP_URL=https://720fundmenow.com`
- `DB_DATABASE=fundmen1_jay`, `DB_USERNAME=fundmen1_jayuser`, `DB_PASSWORD` (quoted — the
  password has `(`, `+`, `@`, `=` in it, so **keep the double quotes**), `DB_HOST=localhost`
- `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, `LOG_LEVEL=warning`
- Your live Authorize.Net, GoHighLevel and Credit Repair Cloud keys
- `APP_KEY` (already generated — do not change it)

> If the site errors with "connection refused", switch `DB_HOST` to `127.0.0.1`.

---

## 5. File permissions (writable folders)
In **File Manager**, set these two folders (and their contents) to **755** (or 775 if 755 fails):
- `storage/`
- `bootstrap/cache/`

If the site shows a 500 with a blank page, it's almost always these permissions.

---

## 6. HTTPS
- cPanel → **SSL/TLS Status** → run **AutoSSL** so the domain has a certificate.
- Because `SESSION_SECURE_COOKIE=true`, the admin login **requires https**. Make sure
  the site loads on `https://` and that http→https redirect is on (cPanel → Domains →
  "Force HTTPS Redirect").

---

## 7. Point Authorize.Net webhooks at the live URL
In the Authorize.Net dashboard → **Account → Webhooks**, set the endpoint to:
```
https://720fundmenow.com/webhooks/authorizenet
```
(Signature verification is already implemented and uses your `AUTHORIZENET_SIGNATURE_KEY`.)

---

## 8. Post-deploy checklist (open these in a browser)
- [ ] `https://720fundmenow.com/` — homepage loads
- [ ] `/pricing` — plans show (Aggressive & Husband/Wife = one-time)
- [ ] `/admin/login` — log in with your admin credentials, dashboard loads
- [ ] Submit the "Free Analysis" form — a row appears in **Admin → All Leads**, and the
      contact appears in **GoHighLevel**
- [ ] `/get-funded` — complete the qualifier, confirm it lands in GHL
- [ ] Do one **real small checkout** and confirm the payment shows in **Admin → Payments**
      and in your Authorize.Net dashboard (then refund it)

---

## Notes / things that are intentionally set
- **No `artisan` commands are required.** Config is read live (no caching), so you don't
  need terminal access. If you ever get cPanel Terminal, `php artisan config:cache` +
  `route:cache` will speed things up.
- **Queue = sync** and **emails via GHL/CRC** — no background worker or SMTP needed.
- **Database = MySQL** (the SQLite file is dev-only).

---

## Troubleshooting `.htaccess`
| Symptom | Cause | Fix |
|---|---|---|
| **500** on every page, log says `Options not allowed here` | Host doesn't grant `AllowOverride Options` | Delete the `Options -Indexes` line from the root `.htaccess` (and `Options -MultiViews -Indexes` from `public/.htaccess`) |
| **500**, log says `Composer detected issues... requires PHP >= 8.2.0` | PHP handler too old | Set `ea-php83`/`ea-php82`, or fix it in MultiPHP Manager |
| Homepage works, every other page **404s** | `mod_rewrite` off, or `public/.htaccess` wasn't uploaded | Enable mod_rewrite; turn on **Show Hidden Files** in File Manager and re-upload both `.htaccess` files |
| `.env` downloads in the browser | Root `.htaccess` missing | Re-upload it — this is urgent, it contains live payment keys |
| AutoSSL won't issue/renew | `/.well-known/` being rewritten | Confirm the `RewriteCond %{REQUEST_URI} !^/\.well-known/` line is present |

After importing the schema, **delete `database/sparkman_mysql_schema.sql`** from the
server (the `.htaccess` already denies it, but there's no reason to leave it there).

---

## ⚠️ SECURITY — do this right after launch
1. **Rotate credentials.** During testing the site ran publicly with debug on, so treat
   these as potentially exposed and regenerate them:
   - Authorize.Net Transaction Key + Signature Key
   - GoHighLevel API key
   - Credit Repair Cloud keys
   - The admin password (`ADMIN_PASSWORD` in `.env`)
2. Confirm `APP_DEBUG=false` in the live `.env` (it is by default here).
3. Confirm `.env` is **not** inside `public_html` (it must not be web-accessible).
