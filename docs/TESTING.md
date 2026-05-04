# Testing Guide - Toko Roti E-Commerce

## Overview
Comprehensive unit testing untuk aplikasi Toko Roti mencakup:
- Admin Panel (Filament)
- Guest Customer Features
- Authenticated Customer Features

## Test Structure

```
tests/
├── Feature/
│   ├── Admin/
│   │   ├── AdminAuthTest.php
│   │   ├── ProductManagementTest.php
│   │   ├── OrderManagementTest.php
│   │   └── CustomerManagementTest.php
│   └── Customer/
│       ├── GuestCustomerTest.php
│       ├── CustomerAuthTest.php
│       ├── CartCheckoutTest.php
│       ├── WishlistTest.php
│       ├── OrderFlowTest.php
│       ├── ProfileAddressTest.php
│       ├── ReviewRatingTest.php
│       └── VoucherSystemTest.php
└── Unit/
    └── ExampleTest.php
```

## Running Tests

### Quick Start (Makefile)

Cara tercepat menggunakan Makefile:

```bash
# Setup test environment (first time only)
make test-setup

# Run all tests
make test

# Run specific test suites
make test-admin
make test-customer
make test-guest

# Run with coverage
make test-coverage

# Run in parallel (faster)
make test-parallel

# Enter container shell
make shell
```

### Using Docker Script

Script `run-tests.sh` juga tersedia:

Script `run-tests.sh` sudah disediakan untuk menjalankan tests di dalam Docker container `php85`:

```bash
# Setup test environment (first time only)
./run-tests.sh setup

# Run all tests
./run-tests.sh all

# Run admin tests only
./run-tests.sh admin

# Run customer tests only
./run-tests.sh customer

# Run guest tests only
./run-tests.sh guest

# Run with coverage
./run-tests.sh coverage

# Run in parallel (faster)
./run-tests.sh parallel
```

### Manual Docker Commands

Jika ingin menjalankan command manual di dalam container:

```bash
# Masuk ke container
docker exec -it php85 bash

# Navigate ke project
cd /var/www/html/toko-roti

# Run tests
php artisan test

# Run specific test
php artisan test tests/Feature/Admin/AdminAuthTest.php

# Run with filter
php artisan test --filter=test_admin_can_access_admin_panel
```

### Local (Without Docker)

Jika menjalankan di local tanpa Docker:
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Admin tests
php artisan test --testsuite=Feature --filter=Admin

# Customer tests
php artisan test --testsuite=Feature --filter=Customer
```

### Run Specific Test File
```bash
php artisan test tests/Feature/Admin/AdminAuthTest.php
```

### Run Specific Test Method
```bash
php artisan test --filter=test_admin_can_access_admin_panel
```

### Run with Coverage
```bash
php artisan test --coverage
```

## Test Coverage

### Admin Panel Tests (4 files, 30+ tests)
✅ **AdminAuthTest** - Authentication & Authorization
- Admin can access admin panel
- Customer cannot access admin panel
- Guest redirected to login
- Role-based permissions
- Inactive admin cannot login

✅ **ProductManagementTest** - Product CRUD
- View products list
- Create product
- Update product
- Delete product (soft delete)
- Auto-generate slug
- Stock management

✅ **OrderManagementTest** - Order Management
- View orders list
- View order detail
- Update order status
- Filter by status
- Search by order number
- Status history tracking

✅ **CustomerManagementTest** - Customer Management
- View customers list
- View customer detail
- Activate/deactivate customer
- Search customers
- View customer orders

### Guest Customer Tests (1 file, 11 tests)
✅ **GuestCustomerTest** - Public Features
- View home page
- Browse products
- View product detail
- Search products
- Filter by category
- View about/contact pages
- Submit contact form
- Cannot access protected routes
- Product view count tracking

### Authenticated Customer Tests (7 files, 70+ tests)
✅ **CustomerAuthTest** - Authentication
- Register with role assignment
- Login/logout
- Password validation
- Inactive customer handling
- Email uniqueness
- Referral code generation
- Last login tracking

✅ **CartCheckoutTest** - Shopping Cart
- Add to cart
- Update quantity
- Remove item
- Calculate subtotal
- Apply voucher
- Checkout process
- Stock decrement
- Cart clearing

✅ **WishlistTest** - Wishlist Management
- Add to wishlist
- Remove from wishlist
- View wishlist
- Move to cart
- Counter display
- Guest protection

✅ **OrderFlowTest** - Order Management
- View order history
- View order detail
- Access control
- Cancel order
- Order number generation
- Payment integration
- Status updates
- Filter by status

✅ **ProfileAddressTest** - Profile & Address
- View/update profile
- Change password
- Password validation
- Add/update/delete address
- Set default address
- Access control

✅ **ReviewRatingTest** - Reviews & Ratings
- Submit review
- Update product rating
- View reviews
- Approval system
- Delete own review
- Access control
- Rating distribution

✅ **VoucherSystemTest** - Voucher System
- Percentage discount
- Fixed discount
- Minimum purchase
- Max discount cap
- Expiry validation
- Usage limit
- Usage tracking
- Active/inactive status

## Database Setup

Tests menggunakan database testing terpisah:
```env
DB_CONNECTION=pgsql
DB_DATABASE=toko_roti_db_test
```

Setiap test menggunakan `RefreshDatabase` trait untuk reset database.

## Factories

Factories tersedia untuk semua models:
- UserFactory
- CustomerFactory
- ProductFactory
- CategoryFactory
- OrderFactory
- AddressFactory
- VoucherFactory
- dll.

## Best Practices

1. **Isolasi Test** - Setiap test independen
2. **RefreshDatabase** - Database di-reset setiap test
3. **Factory Usage** - Gunakan factories untuk test data
4. **Descriptive Names** - Nama test yang jelas
5. **Arrange-Act-Assert** - Struktur test yang konsisten
6. **Guard Testing** - Test dengan guard yang tepat (`customer`, `web`)

## CI/CD Integration

Tests dapat dijalankan di CI/CD pipeline:
```yaml
# .github/workflows/tests.yml
- name: Run Tests
  run: php artisan test --parallel
```

## Troubleshooting

### Database Connection Error
```bash
# Pastikan database test sudah dibuat
createdb toko_roti_db_test

# Atau update phpunit.xml dengan database yang sesuai
```

### Permission Errors
```bash
# Install Shield permissions
php artisan shield:install --fresh
```

### Factory Not Found
```bash
# Generate factory jika belum ada
php artisan make:factory ProductFactory --model=Product
```

## Next Steps

1. ✅ Tambahkan integration tests untuk Midtrans
2. ✅ Tambahkan tests untuk Socialite OAuth
3. ✅ Tambahkan tests untuk Livewire components
4. ✅ Tambahkan browser tests dengan Dusk
5. ✅ Setup CI/CD untuk automated testing

## Total Coverage

- **12 Test Files**
- **110+ Test Cases**
- **Coverage**: Admin Panel, Guest Features, Auth Features
- **All Critical Paths Tested**
