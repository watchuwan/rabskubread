# Panduan Cloudflare Tunnel untuk Midtrans Webhook

## Kenapa Perlu Tunnel?

Midtrans sandbox mengirim notifikasi pembayaran (webhook) ke URL publik. Karena aplikasi berjalan di localhost/Docker, dibutuhkan tunnel agar server Midtrans bisa menjangkau aplikasi kita.

---

## Struktur File

```
/Users/rezalkomdan/development-php/cloudflared/
└── docker-compose.yml
```

---

## Cara Menjalankan

```bash
cd /Users/rezalkomdan/development-php/cloudflared
docker compose up -d
```

## Cara Mematikan

```bash
docker compose down
```

## Cek URL Aktif

```bash
docker logs cloudflared-toko-roti 2>&1 | grep trycloudflare.com
```

Atau ambil URL saja (tanpa info lain):

```bash
docker logs cloudflared-toko-roti 2>&1 | grep -o 'https://[a-zA-Z0-9-]*\.trycloudflare\.com'
```

---

## URL yang Perlu Diset di Midtrans

Setelah tunnel jalan, ambil URL dari log (format: `https://xxxx.trycloudflare.com`), lalu set di:

**Midtrans Sandbox Dashboard → Settings → Configuration**

| Field | URL |
|-------|-----|
| Payment Notification URL | `https://xxxx.trycloudflare.com/webhook/midtrans` |
| Recurring Notification URL | `https://xxxx.trycloudflare.com/webhook/midtrans` |
| Pay Account Notification URL | `https://xxxx.trycloudflare.com/webhook/midtrans` |
| Finish Redirect URL | `https://xxxx.trycloudflare.com/orders` |
| Unfinish Redirect URL | `https://xxxx.trycloudflare.com/orders` |
| Error Redirect URL | `https://xxxx.trycloudflare.com/orders` |

> Ganti `xxxx` dengan subdomain yang muncul di log.

---

## URL Aktif Saat Ini

```
https://premier-turtle-link-billing.trycloudflare.com
```

**Payment Notification URL:**
```
https://premier-turtle-link-billing.trycloudflare.com/webhook/midtrans
```

---

## Catatan Penting

- URL **berubah setiap kali** container di-restart (quick tunnel gratis)
- Setelah restart, **wajib update URL** di Midtrans Dashboard
- Vhost Apache sudah dikonfigurasi dengan wildcard `*.trycloudflare.com` — tidak perlu ubah vhost
- Untuk URL permanen, gunakan [Cloudflare Named Tunnel](https://developers.cloudflare.com/cloudflare-one/connections/connect-apps) dengan akun Cloudflare + domain sendiri

---

## Jika Status Order Masih Pending (Manual Update)

Jika webhook belum masuk, update status manual via tinker:

```bash
docker exec php85 php /var/www/toko-roti/artisan tinker --execute="
\$order = \App\Models\Order::where('order_number', 'ORD-XXXX')->first();
\$payment = \App\Models\Payment::where('order_id', \$order->id)->latest()->first();
\$payment->update(['status' => 'success', 'paid_at' => now()]);
\$order->update(['status' => 'processing', 'paid_at' => now()]);
echo 'Done';
"
```
