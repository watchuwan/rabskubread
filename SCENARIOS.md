# Skenario Lengkap - Toko Roti

Dokumen ini mencakup semua kemungkinan skenario berdasarkan implementasi aktual kode.

---

## 🔐 AUTENTIKASI

### S-AUTH-01: Register berhasil
- Customer isi form → validasi → akun dibuat
- `referral_code` auto-generate: `strtoupper(substr(md5(uniqid()), 0, 8))`
- Jika ada `ref` query param → `referred_by` diisi
- Redirect → `home`

### S-AUTH-02: Register dengan referral code
- Customer buka URL dengan referral code
- `referred_by` diisi dengan `customer_id` referrer
- `Referral` record dibuat
- Reward poin diberikan ke referrer via `Referral::giveReward(100)` setelah order pertama selesai

### S-AUTH-03: Login berhasil
- Guard `customer`, redirect → `home`
- `last_login_at` diupdate

### S-AUTH-04: Login gagal
- Email/password salah → error message
- Akun `is_active = false` → tidak bisa login

### S-AUTH-05: OAuth Google/Facebook berhasil (akun baru)
- Redirect ke provider → callback → cek email
- Email belum ada → buat Customer baru + SocialiteUser
- Auto login → redirect `home`

### S-AUTH-06: OAuth Google/Facebook berhasil (akun sudah ada)
- Email sudah ada di Customer → link SocialiteUser ke Customer yang ada
- Auto login → redirect `home`

### S-AUTH-07: Forgot password
- Input email → kirim link reset
- Token valid 60 menit
- Klik link → form reset password → update password → redirect login

### S-AUTH-08: Token reset password expired
- Klik link setelah 60 menit → error "token expired"
- Customer harus request ulang

### S-AUTH-09: Guest akses halaman auth-only
- Middleware `auth:customer` → redirect ke `/login`

### S-AUTH-10: Customer login akses halaman guest-only
- Middleware `guest:customer` → redirect ke `home`

---

## 🛍️ PRODUK & BROWSE

### S-PROD-01: Browse produk normal
- List produk aktif (`is_active = true`)
- Soft-deleted product tidak tampil

### S-PROD-02: Filter & search
- Filter: kategori, harga, stok
- Search by nama produk

### S-PROD-03: View product detail
- `view_count` increment
- Tampilkan review yang `approved` saja
- Tampilkan rating_average & review_count

### S-PROD-04: Produk habis stok
- `stock = 0` → tombol "Habis" / tidak bisa add to cart
- `in_stock` accessor false

### S-PROD-05: Produk customizable
- `is_customizable = true` → tampilkan opsi customization
- `customization_options` (array) ditampilkan sebagai pilihan

---

## 🛒 CART

### S-CART-01: Add to cart (produk baru)
- Cart belum ada → `Cart::getOrCreateForCustomer($customerId)`
- `CartItem::create` dengan quantity = 1

### S-CART-02: Add to cart (produk sudah ada)
- `CartItem::updateOrCreate` → quantity += 1

### S-CART-03: Update quantity
- Quantity diubah → update `CartItem::quantity`

### S-CART-04: Remove item dari cart
- `CartItem::delete`

### S-CART-05: Cart kosong saat buka checkout
- Redirect ke `cart.index` dengan error "Keranjang Anda kosong"

### S-CART-06: Cart persist setelah logout
- Cart tidak dihapus saat logout, tetap ada saat login kembali

---

## 📦 CHECKOUT

### S-CO-01: Checkout normal
- Load cart items, addresses, shipping methods
- Default address: `is_default = true`, fallback ke address pertama
- Default shipping method: pertama dari list yang tersedia

### S-CO-02: Tidak ada alamat
- Customer belum punya address → harus tambah address dulu

### S-CO-03: Shipping method tersedia
- Filter: `ShippingMethod::whereHas('products')` yang support SEMUA produk di cart
- Hanya tampil method yang `count(products) === count(cartProductIds)`

### S-CO-04: Tidak ada shipping method yang support semua produk
- Fallback ke PICKUP (`code = 'PICKUP'`, `is_active = true`)
- Jika PICKUP tidak ada → tidak ada pilihan pengiriman

### S-CO-05: Apply voucher
- ⚠️ Belum diimplementasi — dispatch toast "Fitur voucher akan segera hadir"

### S-CO-06: Stok tidak mencukupi saat place order
- Validasi per item: `product->stock < item->quantity`
- Error toast: "Stok {nama produk} tidak mencukupi"
- Order tidak dibuat

### S-CO-07: Place order berhasil
1. Validasi form (address, shipping, agreeTerms)
2. Validasi stok semua item
3. `Order::create` (status: `pending`)
4. `OrderItem::create` per item
5. `product->decrementStock(qty)` per item
6. `Payment::create` (status: `pending`)
7. Generate Midtrans Snap Token
8. `payment->update(snap_token, midtrans_transaction_id)`
9. `cart->items()->delete()` (clear cart)
10. Dispatch `cart-updated`
11. Redirect ke `orders.payment`

### S-CO-08: Midtrans Snap Token gagal generate
- Exception di-catch, di-log ke `Log::error`
- Order & Payment tetap dibuat (tanpa snap_token)
- Customer redirect ke payment page tapi tidak bisa bayar (snap_token null)

### S-CO-09: Tidak setuju terms & conditions
- Validasi `agreeTerms: accepted` gagal
- Toast error "Mohon lengkapi semua data"

---

## 💳 PAYMENT

### S-PAY-01: Customer buka payment page
- Load order + snap_token dari payment terbaru
- Jika order sudah `completed` → redirect ke `orders.show`
- Tampilkan Midtrans Snap UI

### S-PAY-02: Payment berhasil (settlement/capture + accept)
- Webhook: `POST /webhook/midtrans`
- Verifikasi signature SHA512
- `payment->update(status: success, paid_at: now())`
- `order->update(status: processing, paid_at: now())`
- `OrderStatusHistory` dicatat otomatis (model boot)

### S-PAY-03: Payment capture tapi fraud
- `transaction_status = capture` + `fraud_status != accept`
- Tidak masuk ke `handleSuccess` → tidak ada aksi (default: null)

### S-PAY-04: Payment dibatalkan/ditolak (cancel/deny)
- `payment->update(status: failed)`
- `order->update(status: cancelled, cancelled_at: now())`
- Stok dikembalikan: `product->increment('stock', qty)` per item
- Dibungkus `DB::transaction`

### S-PAY-05: Payment expired
- `payment->update(status: expired)`
- `order->update(status: cancelled, cancelled_at: now())`
- Stok dikembalikan
- Dibungkus `DB::transaction`

### S-PAY-06: Refund dari Midtrans
- `payment->update(status: refunded)`
- `order->update(status: refunded)`
- Stok dikembalikan HANYA jika order belum `shipped` atau `completed`
- Dibungkus `DB::transaction`

### S-PAY-07: Webhook signature tidak valid
- Return 403 "Invalid signature"
- Log warning

### S-PAY-08: Webhook payment tidak ditemukan
- Return 404 "Payment not found"
- Log warning

### S-PAY-09: Webhook duplikat (order sudah cancelled/refunded)
- `handleCancel`: cek `if (in_array($order->status, ['cancelled', 'refunded'])) return`
- `handleRefund`: cek `if ($order->status === 'refunded') return`
- Idempotent — tidak ada aksi ganda

### S-PAY-10: Snap token null saat buka payment page
- Terjadi jika S-CO-08 (token gagal generate)
- Payment page tampil tapi Midtrans Snap tidak bisa diinisialisasi

---

## 📋 ORDER MANAGEMENT (CUSTOMER)

### S-ORD-01: Lihat daftar order
- Filter by status
- Load `items.product` untuk tampilkan jumlah item

### S-ORD-02: Lihat detail order
- Validasi `order->customer_id === customer->id` → 403 jika bukan miliknya
- Load: `items.product`, `address`, `shippingMethod`, `payment`

### S-ORD-03: Cancel order (pending/processing)
- `canBeCancelled()` = true
- `order->update(status: cancelled, cancelled_at: now())`
- `product->incrementStock(qty)` per item
- `OrderStatusHistory` dicatat otomatis

### S-ORD-04: Cancel order (status lain)
- `canBeCancelled()` = false
- Toast error "Pesanan tidak dapat dibatalkan"

### S-ORD-05: Reorder
- `Cart::getOrCreateForCustomer($customerId)`
- Per item: `CartItem::updateOrCreate` dengan `DB::raw('COALESCE(quantity, 0) + qty')`
- Dispatch `cart-updated`
- Toast success

### S-ORD-06: Download invoice
- ⚠️ Belum diimplementasi — toast "Fitur download invoice akan segera hadir"

### S-ORD-07: Order selesai → earn loyalty points
- Otomatis via model boot `updating`
- Formula: `floor(total_amount / 10000)` poin
- Hanya jika status berubah dari non-completed ke `completed`
- Minimum 1 poin (jika `$points > 0`)

---

## ⭐ REVIEW

### S-REV-01: Tulis review setelah order selesai
- Submit → status `pending`
- Tidak langsung tampil

### S-REV-02: Admin approve review
- Status → `approved`
- `product->rating_average` dan `product->review_count` di-update

### S-REV-03: Admin reject review
- Status → `rejected`
- Tidak tampil di storefront

### S-REV-04: Review tampil di product detail
- Hanya status `approved`

---

## 🏠 ADDRESS

### S-ADDR-01: Tambah address
- Multiple address per customer

### S-ADDR-02: Set default address
- `is_default = true` → pre-select di checkout

### S-ADDR-03: Edit/delete address
- Address yang dipakai di order tetap ada (tidak cascade delete)

---

## 🎟️ VOUCHER

### S-VCH-01: Apply voucher valid
- ⚠️ Di checkout belum diimplementasi (toast info)
- Logic `calculateDiscount()` sudah ada di model

### S-VCH-02: Voucher tidak aktif
- `is_active = false` → `isValid()` return false → discount = 0

### S-VCH-03: Voucher expired
- `now() > valid_until` → `isValid()` return false

### S-VCH-04: Voucher limit habis
- `usage_count >= usage_limit` → `isValid()` return false

### S-VCH-05: Subtotal di bawah minimum
- `subtotal < min_order_amount` → `calculateDiscount()` return 0

### S-VCH-06: Voucher percentage dengan max_discount
- Diskon = `subtotal * value / 100`
- Jika > `max_discount` → dipotong ke `max_discount`
- Diskon tidak melebihi subtotal: `min($discount, $subtotal)`

### S-VCH-07: Voucher fixed
- Diskon = `value` langsung
- Tidak melebihi subtotal: `min($discount, $subtotal)`

---

## 🏆 LOYALTY POINTS

### S-LP-01: Earn poin dari order completed
- Otomatis via model boot
- `floor(total_amount / 10000)` poin
- Expire: `now()->addYear()`

### S-LP-02: Earn poin dari referral
- `Referral::giveReward(100)` dipanggil
- Hanya sekali (`is_rewarded` check)
- Poin masuk ke referrer

### S-LP-03: Redeem poin
- `LoyaltyPoint::redeem($customerId, $points)` → nilai negatif
- Balance berkurang

### S-LP-04: Poin expired
- `expires_at < now()` → tidak dihitung di balance
- `LoyaltyPoint::getBalance()` filter `expires_at > now() OR null`

### S-LP-05: Adjustment manual (admin)
- Admin bisa tambah/kurangi poin manual via Filament

---

## 🔄 PERBANDINGAN PRODUK

### S-CMP-01: Add to compare
- Maksimal 4 produk
- Toggle: jika sudah ada → remove

### S-CMP-02: Melebihi 4 produk
- Tidak bisa tambah lebih dari 4

---

## 👨‍💼 ADMIN - ORDER

### S-ADM-ORD-01: Update status normal
```
pending → processing → shipped → completed
```
- Setiap perubahan → `OrderStatusHistory::record()` otomatis
- `user_id` dari guard `web`

### S-ADM-ORD-02: Status completed → loyalty points
- Auto-earn via model boot (sama dengan S-ORD-07)

### S-ADM-ORD-03: Cancel order dari admin
- Stok dikembalikan manual oleh admin

### S-ADM-ORD-04: Refund
- `order->update(status: refunded)`
- `payment->update(status: refunded)`
- Stok dikembalikan jika belum shipped/completed

### S-ADM-ORD-05: Buat order manual (dari WA)
1. CustomerResource → pilih customer → tab Orders → Create
2. Isi form: order_number (auto), status (default: processing), address (filter by customer), shipping method (auto-isi cost), notes, subtotal, total
3. Save → redirect ke `OrderResource::edit`
4. Tab Items → tambah produk (price auto-fill, subtotal = price × qty)
5. Update subtotal & total di form order

---

## 👨‍💼 ADMIN - PRODUK

### S-ADM-PROD-01: Buat produk
- Upload images → Spatie Media Library, collection `product_images`, disk `public`
- Assign shipping methods (many-to-many)

### S-ADM-PROD-02: Stok habis
- `stock <= low_stock_threshold` → low stock alert di dashboard

### S-ADM-PROD-03: Soft delete produk
- Produk tidak tampil di storefront
- Order history yang sudah ada tetap ada (relasi tidak rusak)

### S-ADM-PROD-04: Stock movement
- `StockMovement::record()` mencatat setiap perubahan stok
- Type: `in` (tambah) / `out` (kurang)
- `user_id` dari guard `web`

---

## 👨‍💼 ADMIN - CUSTOMER

### S-ADM-CUST-01: Ban customer
- `is_active = false` → tidak bisa login

### S-ADM-CUST-02: Adjust loyalty points
- Manual via Filament RelationManager

### S-ADM-CUST-03: Lihat riwayat order customer
- Via `OrdersRelationManager` di CustomerResource

---

## 📊 VISITOR STATS

### S-VIS-01: Kunjungan baru (session baru)
- `visitor_tracked` belum ada di session
- Bukan Livewire request (`X-Livewire` header tidak ada)
- Semua counter naik: today, week, month, total
- `visitor_tracked = true` disimpan ke session

### S-VIS-02: Kunjungan lanjutan (session sama)
- `visitor_tracked` sudah ada → tidak increment

### S-VIS-03: Livewire AJAX request
- Header `X-Livewire` ada → skip tracking

### S-VIS-04: Reset otomatis
- `visitors_today`: reset tengah malam (`now()->endOfDay()`)
- `visitors_week`: reset Senin (`now()->next('Monday')->startOfDay()`)
- `visitors_month`: reset awal bulan (`now()->addMonth()->startOfMonth()`)
- `visitors_total`: tidak pernah reset (no TTL)

---

## ⚠️ EDGE CASES & KONDISI KHUSUS

### S-EDGE-01: Order dibuat tapi Midtrans down
- Snap token gagal → order & payment tetap ada, snap_token null
- Customer tidak bisa bayar via Snap
- Admin bisa update status manual

### S-EDGE-02: Webhook datang sebelum customer redirect
- Race condition: webhook bisa datang lebih cepat dari redirect customer
- Tidak ada locking — potensi double-process minimal karena idempotency check

### S-EDGE-03: Stok habis antara add-to-cart dan checkout
- Validasi stok dilakukan saat `placeOrder()`, bukan saat add-to-cart
- Jika stok habis saat checkout → error per produk

### S-EDGE-04: Voucher habis antara apply dan place order
- ⚠️ Voucher di checkout belum diimplementasi, tidak relevan saat ini

### S-EDGE-05: Customer hapus address yang dipakai di order
- Address di order tetap ada (FK `address_id` di orders)
- Jika address dihapus → `address_id` jadi orphan (perlu soft delete atau restrict)

### S-EDGE-06: Produk dihapus (soft delete) setelah masuk cart
- CartItem masih ada, tapi product soft-deleted
- Perlu handling di checkout agar tidak error

### S-EDGE-07: Order manual WA tanpa address customer
- `OrdersRelationManager` filter address by customer
- Jika customer belum punya address → dropdown kosong, tidak bisa save

### S-EDGE-08: Loyalty points expire saat akan di-redeem
- `getBalance()` sudah filter expired
- Redeem dengan poin yang sudah expired → balance tidak cukup
