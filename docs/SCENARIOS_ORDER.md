# Skenario Pemesanan - Online & WhatsApp

---

## 🌐 PEMESANAN ONLINE (Customer via Website)

### FASE 1: PRA-CHECKOUT

#### SC-O-01: Cart kosong saat buka checkout
```
Customer buka /checkout
→ Cart tidak ada atau items = 0
→ Redirect ke /cart dengan error "Keranjang Anda kosong"
```

#### SC-O-02: Customer belum punya alamat
```
Customer buka /checkout
→ Cart ada, items ada
→ $addresses kosong
→ Dropdown alamat kosong, tidak bisa pilih
→ Customer harus tambah alamat dulu di /addresses/create
```

#### SC-O-03: Semua produk di cart punya shipping method
```
Cart: [Roti A, Roti B]
Roti A support: [JNE, PICKUP]
Roti B support: [JNE, PICKUP]
→ Tampilkan: [JNE, PICKUP] (intersection semua produk)
→ Default: method pertama (sort_order ASC)
```

#### SC-O-04: Sebagian produk tidak punya shipping method
```
Cart: [Roti A, Roti B]
Roti A support: [JNE, PICKUP]
Roti B support: [] (belum di-assign)
→ Filter: tidak ada method yang support SEMUA produk
→ Fallback ke PICKUP (code='PICKUP', is_active=true)
→ Jika PICKUP tidak ada → tidak ada pilihan pengiriman
```

#### SC-O-05: Ganti shipping method
```
Customer pilih method lain
→ updatedSelectedShippingMethod() dipanggil
→ shippingCost diupdate dari method.cost
→ calculateTotals() dijalankan ulang
→ Total berubah di UI
```

#### SC-O-06: Apply voucher
```
Customer input kode voucher → klik Apply
→ ⚠️ Belum diimplementasi
→ Toast: "Fitur voucher akan segera hadir"
→ Discount tetap 0
```

---

### FASE 2: PLACE ORDER

#### SC-O-07: Validasi gagal (form tidak lengkap)
```
Customer klik "Pesan Sekarang" tanpa pilih alamat/shipping/setuju terms
→ ValidationException
→ Toast: "Mohon lengkapi semua data yang diperlukan"
→ Order tidak dibuat
```

#### SC-O-08: Stok habis saat place order
```
Customer sudah di checkout, stok berubah (orang lain beli duluan)
→ Loop validasi: product.stock < item.quantity
→ Toast: "Stok {nama produk} tidak mencukupi"
→ Order tidak dibuat, stok tidak berubah
```

#### SC-O-09: Place order berhasil — semua normal
```
1. Validasi form ✓
2. Validasi stok semua item ✓
3. Order::create → status: pending, order_number: ORD-XXXXX
4. OrderItem::create per item (price, quantity, subtotal)
5. product->decrementStock(qty) per item
6. Payment::create → status: pending, payment_number: PAY-XXXXX
7. Midtrans: generate snap_token
8. payment->update(snap_token, midtrans_transaction_id)
9. cart->items()->delete() → cart bersih
10. Dispatch 'cart-updated' (update badge cart di navbar)
11. Redirect ke /orders/{id}/payment
```

#### SC-O-10: Midtrans gagal generate snap token
```
Midtrans server error / config salah
→ Exception di-catch, Log::error dicatat
→ Order & Payment tetap tersimpan (status: pending)
→ snap_token = null
→ Customer redirect ke payment page
→ Payment page: snap_token null → Midtrans Snap tidak bisa diinisialisasi
→ Customer tidak bisa bayar via UI
→ Admin perlu handle manual
```

---

### FASE 3: PAYMENT

#### SC-O-11: Customer buka payment page — order sudah completed
```
Customer akses /orders/{id}/payment
→ order.status === 'completed'
→ Redirect ke /orders/{id} (detail page)
```

#### SC-O-12: Customer buka payment page — normal
```
Load order + payment terbaru (latest())
snapToken = payment.snap_token
snapUrl = config('services.midtrans.snap_url')
→ Tampilkan Midtrans Snap UI
```

#### SC-O-13: Payment berhasil (settlement)
```
Midtrans kirim webhook POST /webhook/midtrans
transaction_status: 'settlement'
→ Verifikasi signature SHA512 ✓
→ payment->update(status: success, paid_at: now())
→ order->update(status: processing, paid_at: now())
→ OrderStatusHistory dicatat otomatis (model boot)
→ Return 200 'OK'
```

#### SC-O-14: Payment berhasil (capture + accept)
```
transaction_status: 'capture', fraud_status: 'accept'
→ Sama dengan SC-O-13
```

#### SC-O-15: Payment capture tapi fraud
```
transaction_status: 'capture', fraud_status: 'challenge' / bukan 'accept'
→ Tidak masuk handleSuccess
→ Tidak ada aksi (default: null)
→ Order tetap pending, payment tetap pending
→ Admin perlu review manual
```

#### SC-O-16: Payment dibatalkan customer
```
transaction_status: 'cancel'
→ payment->update(status: failed)
→ order->update(status: cancelled, cancelled_at: now())
→ Stok dikembalikan: product->increment('stock', qty) per item
→ DB::transaction (atomic)
→ OrderStatusHistory dicatat otomatis
```

#### SC-O-17: Payment ditolak bank/provider
```
transaction_status: 'deny'
→ Sama dengan SC-O-16
```

#### SC-O-18: Payment expired (tidak bayar dalam batas waktu)
```
transaction_status: 'expire'
→ payment->update(status: expired)
→ order->update(status: cancelled, cancelled_at: now())
→ Stok dikembalikan
→ DB::transaction
```

#### SC-O-19: Refund dari Midtrans
```
transaction_status: 'refund'
→ payment->update(status: refunded)
→ order->update(status: refunded)
→ Stok dikembalikan HANYA jika order belum shipped/completed
   (cek: !in_array(order.status, ['shipped', 'completed']))
→ DB::transaction
```

#### SC-O-20: Webhook duplikat (order sudah cancelled)
```
Webhook datang dua kali untuk cancel/expire
→ handleCancel: cek if (in_array(order.status, ['cancelled', 'refunded'])) return
→ Tidak ada aksi ganda (idempotent)
```

#### SC-O-21: Webhook duplikat (order sudah refunded)
```
→ handleRefund: cek if (order.status === 'refunded') return
→ Tidak ada aksi ganda
```

#### SC-O-22: Webhook signature tidak valid
```
signatureKey tidak cocok
→ Log::warning dicatat
→ Return 403 'Invalid signature'
```

#### SC-O-23: Webhook payment tidak ditemukan
```
payment_number tidak ada di DB
→ Log::warning dicatat
→ Return 404 'Payment not found'
```

---

### FASE 4: PASCA-ORDER

#### SC-O-24: Admin update status → processing → shipped
```
Admin Filament: edit order → ubah status ke 'shipped'
→ order->update(status: shipped, shipped_at: now())
→ OrderStatusHistory dicatat (user_id = admin)
→ Customer lihat di timeline: "Pesanan Dikirim"
```

#### SC-O-25: Admin update status → shipped → completed
```
Admin ubah status ke 'completed'
→ order->update(status: completed, completed_at: now())
→ OrderStatusHistory dicatat
→ Model boot: auto-earn loyalty points
   floor(total_amount / 10000) poin, expire 1 tahun
→ Customer bisa reorder & download invoice (invoice: TODO)
```

#### SC-O-26: Customer cancel order (pending)
```
Customer di /orders/{id} → klik "Batalkan Pesanan" → konfirmasi modal
→ order.status = 'pending' → canBeCancelled() = true
→ order->update(status: cancelled, cancelled_at: now())
→ product->incrementStock(qty) per item
→ OrderStatusHistory dicatat (user_id = null, karena guard customer)
→ Toast: "Pesanan berhasil dibatalkan"
```

#### SC-O-27: Customer cancel order (processing)
```
→ canBeCancelled() = true (processing masih bisa)
→ Sama dengan SC-O-26
```

#### SC-O-28: Customer cancel order (shipped/completed/cancelled)
```
→ canBeCancelled() = false
→ Toast: "Pesanan tidak dapat dibatalkan"
→ Tombol cancel tidak tampil di UI (kondisi: in_array(status, ['pending','processing']))
```

#### SC-O-29: Customer reorder
```
Order completed → klik "Pesan Lagi"
→ Cart::getOrCreateForCustomer($customerId)
→ Per item: CartItem::updateOrCreate
   - Sudah ada di cart → quantity += item.quantity (DB::raw COALESCE)
   - Belum ada → buat baru
→ Dispatch 'cart-updated'
→ Toast: "Produk ditambahkan ke keranjang"
→ Customer lanjut ke checkout
```

#### SC-O-30: Customer download invoice
```
→ ⚠️ Belum diimplementasi
→ Toast: "Fitur download invoice akan segera hadir"
```

#### SC-O-31: Customer akses order milik orang lain
```
GET /orders/{id} dimana order.customer_id !== auth customer
→ abort(403)
```

---

## 📱 PEMESANAN VIA WHATSAPP (Admin Input Manual)

### FASE 1: PERSIAPAN

#### SC-W-01: Customer WA sudah terdaftar di sistem
```
Admin buka Filament → Customers
→ Search nama/email/phone customer
→ Klik customer → masuk ke detail
→ Tab "Orders" → klik "Buat Order Manual"
```

#### SC-W-02: Customer WA belum terdaftar di sistem
```
Admin buka Filament → Customers → Create
→ Isi: name, email, phone, password (generate)
→ Save → customer terbuat
→ Lanjut ke SC-W-01
```

#### SC-W-03: Customer belum punya alamat
```
Admin di CustomerResource → Tab Addresses → Create
→ Isi alamat pengiriman dari info WA customer
→ Save → lanjut buat order
```

---

### FASE 2: BUAT ORDER MANUAL

#### SC-W-04: Buat order manual — normal
```
CustomerResource → Tab Orders → "Buat Order Manual"
Form:
  - order_number: auto (ORD-XXXXX)
  - status: processing (default, skip pending karena sudah konfirmasi WA)
  - address_id: dropdown filter alamat milik customer ini saja
  - shipping_method_id: pilih method → auto-isi shipping_cost
  - voucher_id: opsional
  - notes: catatan dari WA (misal: "minta extra manis")
  - subtotal: isi manual
  - shipping_cost: auto dari shipping method
  - voucher_discount: isi jika ada
  - total_amount: isi manual

→ Save
→ Redirect otomatis ke OrderResource::edit (halaman edit order)
```

#### SC-W-05: Tambah items ke order manual
```
Di halaman Edit Order → Tab "Order Items" → Create
Per item:
  - product_id: pilih produk → price auto-fill dari product.price
  - quantity: isi
  - price: auto-fill, bisa diubah (misal harga spesial WA)
  - subtotal: auto = price × quantity (disabled, dehydrated)

→ Tambah semua item dari pesanan WA
→ Kembali ke form order → update subtotal & total_amount sesuai items
```

#### SC-W-06: Shipping method auto-isi biaya
```
Admin pilih shipping_method_id
→ afterStateUpdated: ShippingMethod::find($state)
→ set('shipping_cost', method.cost)
→ Admin bisa override manual jika perlu
```

#### SC-W-07: Customer WA minta harga spesial
```
Di ItemsRelationManager → isi price manual (bukan dari product.price)
→ subtotal = price × quantity (auto-hitung)
→ Admin update total_amount di form order
```

#### SC-W-08: Order WA tanpa pengiriman (ambil sendiri)
```
Admin pilih shipping method PICKUP
→ shipping_cost = 0
→ total = subtotal - discount
```

---

### FASE 3: PEMBAYARAN ORDER WA

#### SC-W-09: Customer WA bayar transfer bank (manual)
```
Admin konfirmasi pembayaran via WA
→ Filament: Edit Order → ubah status ke 'processing'
   (atau sudah processing dari awal)
→ Isi paid_at = waktu transfer
→ OrderStatusHistory dicatat
→ Tidak ada Payment record (order manual tidak generate Payment otomatis)
   ⚠️ Perlu dibuat manual jika diperlukan
```

#### SC-W-10: Customer WA bayar COD
```
Admin buat order → status: processing
→ Saat barang diterima → admin update status: completed
→ Loyalty points di-earn otomatis
```

#### SC-W-11: Customer WA minta bayar via Midtrans
```
Admin buat order manual
→ Buat Payment record manual di Filament (jika ada PaymentResource)
   atau via tinker/direct DB
→ Generate snap token manual
→ Kirim link payment ke customer via WA
⚠️ Flow ini tidak fully supported di UI saat ini
```

---

### FASE 4: PROSES & PENGIRIMAN

#### SC-W-12: Admin proses order WA
```
Order status: processing
→ Admin siapkan produk
→ Update status: shipped, isi shipped_at
→ Kirim info resi ke customer via WA
→ OrderStatusHistory dicatat
```

#### SC-W-13: Order WA selesai
```
Admin update status: completed, isi completed_at
→ Loyalty points di-earn otomatis: floor(total_amount/10000) poin
→ Customer bisa lihat di /dashboard loyalty points bertambah
```

#### SC-W-14: Customer WA cancel sebelum diproses
```
Admin update status: cancelled, isi cancelled_at
→ Stok dikembalikan manual (admin increment stock di ProductResource)
→ OrderStatusHistory dicatat
```

#### SC-W-15: Customer WA cancel setelah diproses
```
Order sudah processing/shipped
→ Admin pertimbangkan → jika disetujui: update status: cancelled/refunded
→ Stok dikembalikan manual
→ Refund manual ke customer (di luar sistem)
```

---

## 🔄 SKENARIO GABUNGAN (Online + WA)

#### SC-MIX-01: Customer order online, lalu WA untuk ubah pesanan
```
Order online sudah dibuat (pending/processing)
→ Customer WA minta ubah item/alamat
→ Admin edit order di Filament (EditOrder)
→ Ubah items via ItemsRelationManager
→ Update subtotal & total
→ Konfirmasi ke customer via WA
```

#### SC-MIX-02: Customer order online, payment expired, lanjut WA
```
Order online → payment expired → order cancelled → stok kembali
→ Customer WA minta order ulang
→ Admin buat order manual baru (SC-W-04)
→ Atau customer reorder sendiri di website (SC-O-29)
```

#### SC-MIX-03: Customer WA, admin buat order, customer bayar online
```
Admin buat order manual → status: pending
→ Admin generate payment & snap token (manual/tinker)
→ Kirim link /orders/{id}/payment ke customer
→ Customer bayar via Midtrans Snap
→ Webhook update status otomatis
```

#### SC-MIX-04: Order online berhasil, customer WA tanya status
```
Customer WA tanya "pesanan saya sudah sampai mana?"
→ Admin buka Filament → Orders → search order_number
→ Lihat status & OrderStatusHistory
→ Balas WA dengan info status terkini
```

---

## ⚠️ EDGE CASES PEMESANAN

#### SC-EDGE-01: Race condition stok
```
2 customer checkout produk yang sama secara bersamaan, stok = 1
→ Keduanya lolos validasi stok (belum ada locking)
→ Keduanya buat order
→ Stok jadi -1 (oversell)
⚠️ Belum ada DB-level locking di placeOrder()
```

#### SC-EDGE-02: Produk soft-deleted setelah masuk cart
```
Admin hapus produk (soft delete)
→ CartItem masih ada dengan product_id yang soft-deleted
→ Saat checkout: $item->product bisa null atau error
⚠️ Perlu handling di checkout
```

#### SC-EDGE-03: Shipping method dinonaktifkan setelah dipilih
```
Customer sudah di checkout, admin nonaktifkan shipping method
→ Saat loadCheckoutData() ulang: method tidak muncul
→ selectedShippingMethod jadi orphan
→ Validasi 'required|exists:shipping_methods,id' tetap pass (exists tidak cek is_active)
⚠️ Perlu tambah validasi is_active
```

#### SC-EDGE-04: Order manual tanpa items
```
Admin buat order manual → save → redirect ke edit
→ Lupa tambah items di ItemsRelationManager
→ Order ada tapi items kosong
→ Subtotal & total bisa 0
→ Perlu reminder/validasi di UI
```

#### SC-EDGE-05: Webhook datang sebelum redirect customer
```
Midtrans sangat cepat → webhook settlement datang sebelum customer redirect ke payment page
→ Order sudah processing saat customer buka payment page
→ Payment page: order.status !== 'completed' → tetap tampil Snap UI
→ Customer bayar lagi? → Midtrans akan reject (sudah settlement)
→ Tidak ada double charge, tapi UX membingungkan
```

#### SC-EDGE-06: Customer buka payment page order orang lain
```
GET /orders/{id}/payment dimana order.customer_id !== auth customer
→ abort(403)
```

#### SC-EDGE-07: Admin hapus order yang sudah ada payment
```
DeleteAction di EditOrder → order soft-deleted
→ Payment masih ada (tidak cascade)
→ Webhook yang datang kemudian: payment ditemukan, order di-load via payment->order
→ Jika order soft-deleted: order null → error
⚠️ Perlu handling di webhook untuk soft-deleted order
```
