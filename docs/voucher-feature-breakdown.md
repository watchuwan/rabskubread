# Voucher Feature — Analisis & Breakdown

## Status: 🚧 Coming Soon

---

## Kondisi Saat Ini

Fitur cek/apply voucher **sudah ada strukturnya** tapi **belum diimplementasikan secara fungsional**.

### Yang Sudah Ada

| Komponen | File | Status |
|---|---|---|
| Model `Voucher` | `app/Models/Voucher.php` | ✅ Lengkap |
| `isValid()` | `Voucher::isValid()` | ✅ Ada |
| `calculateDiscount()` | `Voucher::calculateDiscount()` | ✅ Ada |
| `scopeValid()` | `Voucher::scopeValid()` | ✅ Ada |
| Admin CRUD Voucher | `app/Filament/Resources/Vouchers/` | ✅ Ada |
| Test voucher | `tests/Feature/Customer/VoucherSystemTest.php` | ✅ 10 test cases |
| Property `voucherCode` di Cart | `CartPage.php` | ✅ Ada |
| Property `voucherCode` di Checkout | `Checkout.php` | ✅ Ada |
| Property `discount` di Cart & Checkout | keduanya | ✅ Ada |
| UI input voucher | `cart-page.blade.php`, `checkout.blade.php` | ✅ Ada (form input) |

### Yang Belum Diimplementasikan

#### `CartPage::applyVoucher()` — `app/Livewire/Customer/Cart/CartPage.php` baris ~163
```php
// SEKARANG — hanya toast placeholder
public function applyVoucher(): void
{
    $this->dispatch('toast', message: 'Fitur voucher akan segera hadir', type: 'info');
}
```

#### `Checkout::applyVoucher()` — `app/Livewire/Customer/Order/Checkout.php` baris ~120
```php
// SEKARANG — hanya toast placeholder
public function applyVoucher(): void
{
    $this->dispatch('toast', message: 'Fitur voucher akan segera hadir', type: 'info');
}
```

---

## Yang Perlu Diimplementasikan

### 1. `applyVoucher()` di CartPage & Checkout

Logic yang harus ada:
```php
public function applyVoucher(): void
{
    if (empty($this->voucherCode)) {
        $this->dispatch('toast', message: 'Masukkan kode voucher', type: 'error');
        return;
    }

    $voucher = Voucher::where('code', $this->voucherCode)->first();

    if (!$voucher || !$voucher->isValid()) {
        $this->dispatch('toast', message: 'Kode voucher tidak valid atau sudah kadaluarsa', type: 'error');
        return;
    }

    $discountAmount = $voucher->calculateDiscount($this->subtotal);

    if ($discountAmount <= 0) {
        $this->dispatch('toast', message: "Minimum order Rp " . number_format($voucher->min_order_amount, 0, ',', '.'), type: 'error');
        return;
    }

    $this->voucherId = $voucher->id;  // hanya di Checkout
    $this->discount  = $discountAmount;
    $this->calculateTotals();

    $this->dispatch('toast', message: "Voucher berhasil diterapkan! Diskon Rp " . number_format($discountAmount, 0, ',', '.'), type: 'success');
}
```

### 2. Simpan `voucher_id` saat `placeOrder()` di Checkout

Di `Checkout::placeOrder()`, kolom `voucher_id` belum diisi ke tabel `orders`:
```php
// Tambahkan ini ke Order::create([...])
'voucher_id' => $this->voucherId,
```

Dan increment usage setelah order dibuat:
```php
if ($this->voucherId) {
    Voucher::find($this->voucherId)?->incrementUsage();
}
```

### 3. Reset voucher saat cart berubah

Jika item cart dihapus/diubah, discount perlu di-reset agar tidak salah hitung:
```php
public function removeItem(...): void
{
    // ... existing code ...
    $this->discount   = 0;
    $this->voucherCode = '';
    $this->loadCart();
}
```

---

## Alur Lengkap yang Diharapkan

```
Customer input kode voucher
        ↓
applyVoucher() dipanggil
        ↓
Cari voucher by code → cek isValid()
        ↓
calculateDiscount(subtotal) → set $this->discount
        ↓
calculateTotals() → total berkurang
        ↓
Saat placeOrder() → simpan voucher_id ke orders
        ↓
incrementUsage() → usage_count +1
        ↓
VoucherUsage record dibuat (opsional, tabel sudah ada)
```

---

## File yang Perlu Diubah

1. `app/Livewire/Customer/Cart/CartPage.php` — implementasi `applyVoucher()`
2. `app/Livewire/Customer/Order/Checkout.php` — implementasi `applyVoucher()` + update `placeOrder()`
3. `resources/views/livewire/customer/cart/cart-page.blade.php` — tampilkan info diskon voucher
4. `resources/views/livewire/customer/order/checkout.blade.php` — tampilkan info diskon voucher

---

## Catatan

- Model `Voucher` dan semua logic kalkulasi sudah siap pakai, tidak perlu diubah.
- 10 test cases di `VoucherSystemTest.php` sudah cover semua edge case (expired, min order, usage limit, dll).
- Tabel `voucher_usages` sudah ada di database, bisa dipakai untuk tracking per-customer usage.
- Kolom `voucher_id` di tabel `orders` sudah ada (migration `2026_03_10_204816_add_voucher_foreign_key_to_orders_table.php`).
