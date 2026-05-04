# Setup Email (SMTP) untuk Forgot Password

Fitur forgot password memerlukan konfigurasi email untuk mengirim link reset password ke customer.

## Opsi 1: Gmail SMTP (Recommended untuk Development)

### 1. Setup Gmail App Password
1. Login ke Google Account: https://myaccount.google.com/
2. Aktifkan 2-Step Verification
3. Buat App Password:
   - Go to: https://myaccount.google.com/apppasswords
   - Select app: Mail
   - Select device: Other (Custom name) → "Toko Roti"
   - Copy generated password (16 karakter)

### 2. Update .env
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password-16-chars
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Toko Roti"
```

---

## Opsi 2: Mailtrap (Recommended untuk Testing)

Mailtrap adalah fake SMTP server untuk testing email tanpa mengirim ke email asli.

### 1. Daftar Mailtrap
- Website: https://mailtrap.io/
- Buat akun gratis
- Buat inbox baru

### 2. Copy Credentials
Di inbox Mailtrap, pilih "Show Credentials" → Laravel

### 3. Update .env
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tokoroti.com
MAIL_FROM_NAME="Toko Roti"
```

---

## Opsi 3: Mailgun (Production)

### 1. Setup Mailgun
- Website: https://www.mailgun.com/
- Daftar dan verify domain
- Get API credentials

### 2. Install Mailgun Package
```bash
composer require symfony/mailgun-mailer symfony/http-client
```

### 3. Update .env
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-api-key
MAILGUN_ENDPOINT=api.mailgun.net
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Toko Roti"
```

---

## Opsi 4: Log Driver (Development Only)

Untuk development tanpa SMTP, email akan disimpan di log file.

### Update .env
```env
MAIL_MAILER=log
```

Email akan tersimpan di: `storage/logs/laravel.log`

**Note:** Link reset password tetap bisa dicopy dari log untuk testing.

---

## Testing Email

### 1. Test via Tinker
```bash
php artisan tinker
```

```php
use App\Models\Customer;
use Illuminate\Support\Facades\Password;

$customer = Customer::first();
Password::broker('customers')->sendResetLink(['email' => $customer->email]);
```

### 2. Test via Browser
1. Buka: http://toko-roti.localhost:8085/forgot-password
2. Masukkan email customer yang terdaftar
3. Klik "Kirim Link Reset Password"
4. Cek email inbox (atau Mailtrap/log)

---

## Troubleshooting

### Error: "Connection could not be established with host"
- Cek MAIL_HOST dan MAIL_PORT
- Pastikan firewall tidak block port 587/465
- Coba ganti MAIL_ENCRYPTION dari `tls` ke `ssl` atau sebaliknya

### Error: "Authentication failed"
- Gmail: Pastikan menggunakan App Password, bukan password biasa
- Cek MAIL_USERNAME dan MAIL_PASSWORD

### Email tidak terkirim (no error)
- Cek queue: `php artisan queue:work`
- Cek log: `tail -f storage/logs/laravel.log`

### Link reset password tidak berfungsi
- Pastikan APP_URL di .env sesuai dengan domain yang digunakan
- Cek apakah token sudah expired (default 60 menit)

---

## Production Recommendations

1. **Gunakan Queue** untuk mengirim email async:
   ```env
   QUEUE_CONNECTION=redis
   ```
   
2. **Setup Email Service** yang reliable:
   - Mailgun (recommended)
   - SendGrid
   - Amazon SES
   - Postmark

3. **Monitor Email Delivery**:
   - Setup webhook untuk bounce/complaint
   - Track email open rate
   - Monitor spam score

4. **Security**:
   - Jangan commit .env ke git
   - Gunakan environment variables di production
   - Rotate SMTP credentials secara berkala

---

## Docker Setup (Current Project)

Jika menggunakan Docker, pastikan container bisa akses internet untuk SMTP:

```bash
# Test koneksi dari container
docker exec php85 bash -c "telnet smtp.gmail.com 587"
```

Jika gagal, cek network settings di docker-compose.yml.

---

## Quick Start (Mailtrap)

Cara tercepat untuk testing:

1. Daftar Mailtrap: https://mailtrap.io/
2. Copy credentials ke .env
3. Restart server: `php artisan config:clear`
4. Test forgot password
5. Cek email di Mailtrap inbox

✅ Email akan muncul di Mailtrap tanpa dikirim ke email asli!
