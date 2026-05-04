# Workflow Toko Roti - Customer & Admin

## 🛒 CUSTOMER WORKFLOWS

### 1. Registration & Authentication
**Flow:**
```
Register → Login → Dashboard
   ↓
OAuth (Google/Facebook) → Auto Login → Dashboard
   ↓
Forgot Password → Email Link → Reset Password → Login
```

**Models:** `Customer`, `SocialiteUser`

**Kondisi:**
- Register dengan email/password
- Login via Google/Facebook OAuth (Socialite)
- Forgot password & reset via email link
- Token reset password: 60 menit
- Referral code auto-generate saat register (`strtoupper(substr(md5(uniqid()), 0, 8))`)
- Customer yang direferral dicatat di `referred_by`
- Guard terpisah: `customer` (bukan `web`)
- Redirect setelah login → `home`
- Redirect setelah logout → `home`

---

### 2. Browse & Search Products
**Flow:**
```
Homepage → Browse Categories → Product List → Product Detail
   ↓
Search → Filter → Product Detail
```

**Models:** `Product`, `Category`, `ProductVariant`

**Kondisi:**
- Product punya: `name`, `slug`, `description`, `price`, `stock`, `low_stock_threshold`, `sku`
- Info tambahan: `ingredients` (array), `allergens` (array), `preparation_time`, `calories`
- Product bisa customizable: `is_customizable`, `customization_options` (array)
- Product punya `size`
- Images via Spatie Media Library, collection: `product_images`, disk: `public`, dengan responsive images
- Rating: `rating_average`, `review_count` (auto-update saat review diapprove)
- `view_count` tercatat
- Soft delete pada product
- Category: hierarchical (parent-child), punya `slug`

---

### 3. Wishlist Management
**Flow:**
```
Product Detail → Add to Wishlist → Wishlist Page
   ↓
Move to Cart → Cek apakah sudah di cart
   ├── Sudah ada → increment quantity
   └── Belum ada → buat CartItem baru
   ↓
Remove from Wishlist
```

**Models:** `Wishlist`, `Product`, `Customer`, `Cart`, `CartItem`

**Kondisi:**
- Wishlist per customer per product (unique)
- Move to cart: `CartItem::updateOrCreate` dengan `quantity += 1`
- Cart dibuat otomatis jika belum ada: `Cart::getOrCreateForCustomer($customerId)`

---

### 4. Shopping Cart
**Flow:**
```
Product Detail → Add to Cart → Cart Page
   ↓
Update Quantity / Remove Item → Proceed to Checkout
```

**Models:** `Cart`, `CartItem`, `Product`

**Kondisi:**
- Satu cart per customer (`HasOne`)
- CartItem: unique constraint `(cart_id, product_id)`
- Jika product sudah ada di cart → increment quantity
- Cart persist per customer (tidak hilang saat logout)

---

### 5. Checkout & Order Placement
**Flow:**
```
Cart → Checkout Page
   ↓
Select Address → Select Shipping Method → Apply Voucher (opsional) → Review → Agree Terms
   ↓
Validasi stok → Place Order
   ↓
Generate Order (ORD-XXXXX) → Generate Payment (PAY-XXXXX) → Midtrans Snap Token
   ↓
Redirect ke Payment Page → Clear Cart
```

**Models:** `Order`, `OrderItem`, `Payment`, `Address`, `ShippingMethod`, `Voucher`, `VoucherUsage`

**Kondisi:**
- Order number: `ORD-` + `strtoupper(uniqid())` (auto di model boot)
- Payment number: `PAY-` + `strtoupper(uniqid())` (auto di model boot)
- Subtotal dihitung dari cart items
- Tax 11% dihitung di checkout
- Shipping cost dari `ShippingMethod::cost`
- Voucher discount: `Voucher::calculateDiscount($subtotal)`
- Total = subtotal + tax + shipping - voucher_discount
- Stok divalidasi sebelum order dibuat
- Stok dikurangi saat order dibuat
- Cart di-clear setelah order berhasil
- `VoucherUsage` dicatat, `voucher->incrementUsage()` dipanggil
- Order status awal: `pending`
- Payment status awal: `pending`

**Shipping Method Logic:**
- Hanya tampilkan shipping methods yang support SEMUA produk di cart (many-to-many `product_shipping_method`)
- Fallback ke PICKUP jika produk belum ada shipping method yang di-assign
- PICKUP selalu tersedia (gratis)

**Voucher Validation:**
- `is_active = true`
- `valid_from <= now() <= valid_until`
- `usage_count < usage_limit` (jika ada limit)
- `subtotal >= min_order_amount`
- Tipe: `percentage` atau `fixed`
- `max_discount` membatasi diskon maksimal untuk tipe percentage

---

### 6. Payment Processing
**Flow:**
```
Payment Page → Midtrans Snap → Pilih metode pembayaran
   ↓
Callback Midtrans Webhook (POST /webhook/midtrans)
   ├── settlement/capture → Payment: success, Order: processing, catat paid_at
   ├── pending          → Payment: pending, Order: pending
   ├── deny/cancel      → Payment: failed, Order: pending
   └── expire           → Payment: expired, Order: pending
```

**Models:** `Payment`, `Order`

**Payment Statuses:** `pending` | `success` | `failed` | `expired` | `refunded`

**Order Statuses:** `pending` | `processing` | `shipped` | `completed` | `cancelled` | `refunded`

**Kondisi:**
- Webhook route: `POST /webhook/midtrans` (tanpa CSRF)
- Snap token disimpan di `payments.snap_token`
- Midtrans response disimpan di `payments.midtrans_response` (JSON/array)
- `midtrans_transaction_id` disimpan saat callback
- `paid_at` diisi saat payment success
- Setiap perubahan status order → `OrderStatusHistory::record()` otomatis (via model boot `updating`)
- `user_id` di history: admin jika via guard `web`, null jika customer/webhook

---

### 7. Order Management (Customer)
**Flow:**
```
My Orders → Filter by Status → View Order Detail
   ↓
Cancel Order
   ├── Status pending/processing → bisa dibatalkan
   │   └── Stock dikembalikan per item → Order: cancelled, catat cancelled_at
   └── Status lain → tidak bisa dibatalkan
   ↓
Reorder → Tambah semua items ke cart (updateOrCreate quantity)
```

**Models:** `Order`, `OrderItem`, `OrderStatusHistory`, `Payment`

**Kondisi:**
- `canBeCancelled()`: hanya status `pending` atau `processing`
- Stok dikembalikan: `product->incrementStock($item->quantity)` per item
- Reorder: `CartItem::updateOrCreate` dengan `DB::raw('COALESCE(quantity, 0) + qty')`
- Download invoice: belum diimplementasi (TODO)
- Order detail load: `items.product`, `address`, `shippingMethod`, `payment`
- Customer hanya bisa lihat order miliknya (validasi `customer_id`)

---

### 8. Product Reviews
**Flow:**
```
Order selesai → Write Review → Submit
   ↓
Status: pending (menunggu moderasi admin)
   ↓
Admin Approve → Review published, product rating & review_count di-update
Admin Reject → Review rejected, tidak tampil
```

**Models:** `ProductReview`, `Product`, `Order`, `Customer`

**Kondisi:**
- Rating 1-5 bintang
- Review bisa include images
- Status review: `pending` | `approved` | `rejected`
- Hanya review `approved` yang tampil di product detail
- Saat approve: `product->rating_average` dan `product->review_count` di-update otomatis
- Review hanya bisa ditulis setelah order delivered/completed

---

### 9. Address Management
**Flow:**
```
Profile → Addresses → Add/Edit/Delete
   ↓
Set Default Address → Digunakan di checkout
```

**Models:** `Address`, `Customer`

**Kondisi:**
- Multiple addresses per customer
- Field: `label`, `recipient_name`, `phone`, `address`, `city`, `province`, `postal_code`
- Default address digunakan sebagai pre-select di checkout
- Address dipakai di `OrdersRelationManager` (filter by customer)

---

### 10. Voucher System
**Flow:**
```
Checkout → Input kode voucher → Validate
   ├── Valid → Hitung diskon → Tampilkan di summary
   └── Invalid → Tampilkan error (tidak aktif / expired / limit / min. pembelian)
   ↓
Order placed → VoucherUsage::create → voucher->incrementUsage()
```

**Models:** `Voucher`, `VoucherUsage`, `Order`

**Voucher Fields:** `code`, `name`, `description`, `type`, `value`, `min_order_amount`, `max_discount`, `usage_limit`, `usage_count`, `valid_from`, `valid_until`, `is_active`

**Tipe:** `percentage` | `fixed`

**Kondisi:**
- Satu voucher per order
- `calculateDiscount()`: percentage = `subtotal * value / 100`, fixed = `value`
- Diskon dibatasi `max_discount` (untuk percentage)
- Diskon tidak melebihi subtotal: `min($discount, $subtotal)`
- Scope `valid()`: active + date range + usage limit

---

### 11. Loyalty Points
**Flow:**
```
Order completed → Earn points (1 poin per Rp 10.000)
   ↓
Referral berhasil → Referrer dapat reward points via Referral::giveReward()
   ↓
Redeem points → Diskon order berikutnya
```

**Models:** `LoyaltyPoint`, `Customer`, `Referral`

**Point Types (field `type`):** `order_completed` | `referral_reward` | `redemption` | `adjustment`
**Reference Types (field `reference_type`):** `earn` | `redeem`

**Kondisi:**
- Earn otomatis saat order status berubah ke `completed` (via model boot)
- Formula: `floor(total_amount / 10000)` poin
- Points expire: 1 tahun dari tanggal earn (`now()->addYear()`)
- Balance: sum points yang belum expired
- Redeem: points disimpan sebagai nilai negatif
- Referral reward: `Referral::giveReward($points = 100)` — default 100 poin
- Referral hanya bisa di-reward sekali (`is_rewarded`)

---

### 12. Product Comparison
**Flow:**
```
Product Detail → Add to Compare (max 4 produk)
   ↓
Compare Page → Side-by-side comparison
   ↓
Remove from Compare
```

**Models:** `ProductComparison`, `Product`, `Customer`

**Kondisi:**
- Maksimal 4 produk per customer
- Toggle add/remove
- Bandingkan: price, rating, ingredients, allergens, dll.

---

## 👨‍💼 ADMIN WORKFLOWS

### 1. Order Management (Admin)
**Flow:**
```
Orders → View Detail
   ↓
Update Status
   ├── pending → processing (setelah payment confirmed)
   ├── processing → shipped (catat shipped_at)
   ├── shipped → completed (catat completed_at) → auto-earn loyalty points customer
   ├── pending/processing → cancelled (catat cancelled_at)
   └── completed → refunded → update payment status ke refunded
```

**Status Transitions:**
```
pending → processing → shipped → completed
   ↓                               ↓
cancelled                       refunded
```

**Kondisi:**
- Setiap perubahan status → `OrderStatusHistory` dicatat otomatis (model boot)
- `user_id` di history diisi dari guard `web` (admin)
- Loyalty points di-earn otomatis saat status → `completed`
- Stok dikembalikan jika order dibatalkan (manual oleh admin)

---

### 2. Order Manual (dari WA)
**Flow:**
```
Customers → Pilih Customer → Tab Orders → Buat Order Manual
   ↓
Isi: order_number (auto), status (default: processing), alamat (filter by customer),
     shipping method (auto-isi biaya), voucher (opsional), catatan WA, subtotal, total
   ↓
Save → Redirect ke OrderResource Edit
   ↓
Tab Items → Tambah produk (auto-hitung subtotal & price dari product)
   ↓
Update subtotal & total di form order
```

**Kondisi:**
- `address_id` di-filter hanya alamat milik customer tersebut
- `shipping_method_id` auto-isi `shipping_cost` saat dipilih
- Setelah create → redirect ke `OrderResource::getUrl('edit', $record)`
- Items: `price` auto-fill dari product, `subtotal = price * quantity`
- Status default `processing` (skip pending karena sudah konfirmasi via WA)

---

### 3. Product Management
**Flow:**
```
Products → Create/Edit
   ↓
Set: category, name, price, stock, low_stock_threshold, SKU
Set: ingredients (array), allergens (array), preparation_time, calories
Set: is_customizable, customization_options, size
Upload images (Spatie Media Library, collection: product_images)
Assign shipping methods (many-to-many)
   ↓
Publish (is_active = true)
```

**Stock Management:**
- Auto-decrement saat order dibuat
- Auto-increment saat order dibatalkan (`incrementStock()`)
- `StockMovement::record()` mencatat: type (`in`/`out`), qty, stock_before, stock_after, reference
- Low stock alert jika `stock <= low_stock_threshold`
- `user_id` di StockMovement dari guard `web`

---

### 4. Customer Management
**Flow:**
```
Customers → View Detail
   ↓
Tab: Orders | Addresses | Reviews | Loyalty Points (RelationManagers)
   ↓
Adjust loyalty points manual
Ban customer (is_active = false)
```

**Kondisi:**
- `total_orders`: accessor `getTotalOrdersAttribute()`
- `total_spent`: sum completed orders `getTotalSpentAttribute()`
- `loyalty_balance`: `LoyaltyPoint::getBalance($id)` accessor
- OAuth accounts linked via `SocialiteUser` (HasMany)
- `last_login_at` diupdate saat login

---

### 5. Review Moderation
**Flow:**
```
Reviews → Filter: pending/approved/rejected
   ↓
Approve → product rating & review_count di-update
Reject → review tidak tampil
```

**Kondisi:**
- Hanya review `approved` tampil di storefront
- Update rating: recalculate `rating_average` dan `review_count` di product

---

### 6. Voucher Management
**Kondisi:**
- Tipe: `percentage` | `fixed`
- `max_discount`: batas diskon untuk tipe percentage
- `usage_limit`: null = unlimited
- Scope `valid()` tersedia untuk query

---

### 7. Settings Management
**Kondisi:**
- Key-value store di tabel `settings`
- Di-cache 3600 detik via `ViewServiceProvider`
- Cache key: `app_settings`
- Di-share ke semua views sebagai `$settings`

---

### 8. Visitor Stats
**Kondisi:**
- Tracking via middleware `TrackVisitor` (alias: `track-visitor`)
- Diterapkan di semua route customer: `['web', 'set-locale', 'track-visitor']`
- Skip Livewire AJAX requests (`X-Livewire` header)
- Unique per session (`visitor_tracked` di session)
- Cache driver: `database`
- Keys: `visitors_today` (reset tengah malam), `visitors_week` (reset Senin), `visitors_month` (reset awal bulan), `visitors_total` (permanent)
- TTL menggunakan Carbon instance (`now()->endOfDay()`, dll.)
- Ditampilkan via Livewire component `<livewire:visitor-stats />` di footer

---

## 🔄 KEY INTEGRATIONS

### Midtrans Payment Gateway
- Snap Token generation di checkout
- Webhook: `POST /webhook/midtrans` (tanpa CSRF via `withoutMiddleware`)
- Response disimpan di `payments.midtrans_response` (cast array)
- `midtrans_transaction_id` dari callback

### Spatie Media Library
- Product images: collection `product_images`, disk `public`
- Responsive images enabled
- Image conversion: `thumb`

### Laravel Socialite
- Provider: Google, Facebook
- Routes: `GET /auth/{provider}/redirect`, `GET /auth/{provider}/callback`
- Account linking via `SocialiteUser` model

---

## 🔐 SECURITY & PERMISSIONS

### Customer Guard (`customer`)
- Middleware `auth:customer` untuk route yang butuh login
- Middleware `guest:customer` (alias custom) untuk route guest-only
- `RedirectIfAuthenticated` redirect ke `home` jika sudah login

### Admin Guard (`web`)
- Filament Shield: roles & permissions
- `canAccessPanel()` di Customer model: cek role `super_admin`/`admin_staff`/`staff` + `is_active`
- `OrderStatusHistory` dan `StockMovement` catat `user_id` dari guard `web`

---

## 🎯 BUSINESS RULES

1. **Stock:** Decrement saat order dibuat, increment saat dibatalkan. Validasi sebelum checkout.
2. **Shipping:** Hanya tampil method yang support semua produk di cart. Fallback ke PICKUP.
3. **Voucher:** Satu per order. Validasi: active + date + usage limit + min purchase.
4. **Reviews:** Hanya setelah order selesai. Butuh approval admin. Auto-update rating.
5. **Loyalty Points:** 1 poin per Rp 10.000 dari completed order. Expire 1 tahun. Referral default 100 poin.
6. **Cancellation:** Hanya `pending`/`processing`. Stok dikembalikan.
7. **Cart:** Satu cart per customer. Unique per product. Increment jika sudah ada.
8. **Order Manual (WA):** Dibuat admin via CustomerResource → OrdersRelationManager → redirect ke OrderResource untuk tambah items.
