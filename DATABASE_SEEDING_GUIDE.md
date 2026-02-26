# 📊 DOKUMENTASI DATA DUMMY - Pemesanan Tiket Bioskop

## ✅ SEEDING BERHASIL DILAKUKAN

Database telah diisi dengan data dummy menggunakan `php artisan migrate:refresh --seed`

---

## 📈 RINGKASAN DATA

| Tabel | Jumlah | Keterangan |
|-------|--------|------------|
| **Users** | 7 | 2 Admin + 5 Customer |
| **Categories** | 12 | Genre film (Action, Drama, Comedy, dll) |
| **Studios** | 8 | Studio 1-8 dengan berbagai type (2D, 3D, IMAX, Dolby) |
| **Seats** | 640 | 80 kursi × 8 studio (A1-H10) |
| **Films** | 15 | Film terbaru dengan berbagai genre |
| **Showtimes** | 309 | Jadwal tayang untuk 7 hari ke depan |
| **ShowtimeSeats** | 24,720 | Semua kursi × semua showtime |
| **Orders** | 17 | Sample order dengan berbagai status |
| **Tickets** | 42 | Tiket terkait order |
| **Payments** | 17 | Payment record terkait order |

---

## 🔐 LOGIN CREDENTIALS

### **Admin Account**
```
Email: admin@bioskopapp.com
Password: admin123
```

### **Customer Accounts**
```
Email: john@example.com     Password: password123
Email: jane@example.com     Password: password123
Email: bob@example.com      Password: password123
Email: alice@example.com    Password: password123
Email: charlie@example.com  Password: password123
```

---

## 📋 DETAIL DATA

### 1. **Users (7 total)**

#### Admin (2):
- **Admin Utama** - admin@bioskopapp.com
- **Admin Bioskop** - admin2@bioskopapp.com

#### Customer (5):
- **John Doe** - john@example.com
- **Jane Smith** - jane@example.com
- **Bob Wilson** - bob@example.com
- **Alice Brown** - alice@example.com
- **Charlie Davis** - charlie@example.com

---

### 2. **Categories (12 total)**
```
Action, Drama, Comedy, Horror, Sci-Fi, Romance, 
Thriller, Animation, Documentary, Fantasy, Adventure, Crime
```

---

### 3. **Studios (8 total)**

| Studio | Type | Kapasitas |
|--------|------|-----------|
| Studio 1 | 2D | 80 seats |
| Studio 2 | 2D | 80 seats |
| Studio 3 | 3D | 80 seats |
| Studio 4 | IMAX | 80 seats |
| Studio 5 | Dolby | 80 seats |
| Studio 6 | 2D | 80 seats |
| Studio 7 | 3D | 80 seats |
| Studio 8 | IMAX | 80 seats |

**Seat Types:**
- **Regular**: Rows C-H (standard seats)
- **VIP**: Rows A-B (front rows, premium price)
- **Couple**: Rows D-E, columns 4-7 (middle section)

---

### 4. **Films (15 total)**

| # | Film Title | Duration | Rating | Categories |
|---|------------|----------|--------|------------|
| 1 | Avatar: The Way of Water | 192 min | PG-13 | Action, Adventure, Sci-Fi |
| 2 | Avengers: Secret Wars | 165 min | PG-13 | Action, Adventure, Sci-Fi |
| 3 | The Batman 2 | 155 min | PG-13 | Action, Crime, Drama |
| 4 | Dune: Part Three | 168 min | PG-13 | Sci-Fi, Adventure, Drama |
| 5 | Oppenheimer | 180 min | R | Drama, Thriller |
| 6 | Barbie | 114 min | PG-13 | Comedy, Adventure, Fantasy |
| 7 | Spider-Man: Across the Spider-Verse | 140 min | PG | Animation, Action, Adventure |
| 8 | John Wick: Chapter 5 | 145 min | R | Action, Thriller, Crime |
| 9 | The Conjuring: Last Rites | 115 min | R | Horror, Thriller |
| 10 | Frozen 3 | 105 min | PG | Animation, Adventure, Fantasy |
| 11 | Mission: Impossible 8 | 158 min | PG-13 | Action, Adventure, Thriller |
| 12 | A Quiet Place: Day One | 100 min | PG-13 | Horror, Thriller, Sci-Fi |
| 13 | Inside Out 2 | 96 min | PG | Animation, Comedy, Drama |
| 14 | Deadpool 3 | 127 min | R | Action, Comedy, Sci-Fi |
| 15 | Gladiator 2 | 148 min | R | Action, Drama, Adventure |

---

### 5. **Showtimes (309 total)**

**Coverage:**
- ✅ 7 hari ke depan (hari ini + 6 hari berikutnya)
- ✅ Setiap studio menampilkan 2-3 film per hari
- ✅ Setiap film memiliki 2-3 jadwal tayang per hari
- ✅ Jadwal mulai dari 10:00 - 23:00

**Price Range:**
- **2D**: Rp 35,000 - Rp 50,000
- **3D**: Rp 50,000 - Rp 65,000
- **IMAX**: Rp 75,000 - Rp 90,000
- **Dolby**: Rp 60,000 - Rp 75,000

**Price Variations:**
- +Rp 10,000 untuk evening shows (after 5 PM)
- +Rp 5,000 untuk weekend shows

---

### 6. **ShowtimeSeats (24,720 total)**

**Mapping:**
- Setiap showtime memiliki 80 kursi (semua kursi di studio tersebut)
- Total: 309 showtimes × 80 seats = 24,720 showtime_seats

**Status Distribution:**
- **Available**: Sebagian besar kursi masih available
- **Booked**: Kursi yang sudah dipesan (dari order paid)
- **Locked**: Kursi yang sedang dikunci (5 menit timer)

---

### 7. **Orders (17 total)**

**Status Breakdown:**
```
✅ Paid:      8 orders (47%)
⏳ Pending:   3 orders (18%) - Dengan countdown timer aktif
❌ Canceled:  4 orders (24%)
⚠️  Failed:   2 orders (12%)
```

**Order Details:**
- Setiap order memiliki 1-3 tickets
- Total price bervariasi berdasarkan jumlah kursi dan harga showtime
- Booking code unik (8 karakter random uppercase)
- Pending orders memiliki expires_at (10 menit dari creation)

---

### 8. **Tickets (42 total)**

**Distribution:**
- Rata-rata 2-3 tickets per order
- Setiap ticket memiliki:
  - Unique QR code (UUID format)
  - Price sesuai harga showtime saat booking
  - Seat assignment yang spesifik

---

### 9. **Payments (17 total)**

**Payment Status:**
```
✅ Success:  8 payments (matching paid orders)
⏳ Pending:  3 payments (matching pending orders)
❌ Failed:   6 payments (matching canceled + failed orders)
```

**Payment Method:**
- Semua payment menggunakan `midtrans` sebagai payment method
- Payment reference untuk successful payments: `MIDTRANS-XXXXXXXXXX`

---

## 🎯 TESTING GUIDE

### **Test sebagai Admin:**

1. **Login Admin**
   ```
   URL: /admin/login
   Email: admin@bioskopapp.com
   Password: admin123
   ```

2. **Dashboard Admin**
   - View total films, showtimes, orders
   - Navigate ke Film, Showtime, Orders management

3. **Manage Films**
   - View 15 films yang sudah ada
   - Add new film dengan poster upload
   - Edit/Delete existing films

4. **Manage Showtimes**
   - View 309 showtimes
   - Add new showtime (dengan validasi bentrok jadwal)
   - Edit/Delete showtimes

5. **Manage Orders**
   - View 17 orders dengan berbagai status
   - Cancel pending orders (akan release seats)
   - View order detail dengan payment info

---

### **Test sebagai Customer:**

1. **Login Customer**
   ```
   URL: /login/customer
   Email: john@example.com
   Password: password123
   ```

2. **Browse Films**
   - Homepage menampilkan 15 films
   - Hero slider dengan 5 film terbaru
   - Click film untuk lihat detail

3. **Book Tickets**
   - Pilih film → Pilih showtime → Pilih kursi
   - Lock kursi (5 menit timer)
   - Checkout dengan validasi
   - Payment via Midtrans (sandbox)

4. **View Orders**
   - View "Pesanan Saya" dengan stats cards
   - Countdown timer untuk pending orders
   - Order detail dengan QR code tickets
   - Retry payment untuk pending orders

5. **Profile Management**
   - Update name, phone, password
   - Upload avatar photo

---

## 🔄 CARA RE-SEED DATABASE

### **Full Reset (Drop & Recreate All Tables)**
```bash
php artisan migrate:refresh --seed
```

⚠️ **PERINGATAN:** Ini akan menghapus SEMUA data dan mengulang ke kondisi awal!

### **Seed Specific Tables Only**
```bash
# Seed users only
php artisan db:seed --class=UserSeeder

# Seed films only
php artisan db:seed --class=FilmSeeder

# Seed orders only (requires users, films, showtimes)
php artisan db:seed --class=OrderSeeder
```

### **Seed Order (Recommended)**
```bash
1. UserSeeder (creates admin & customers)
2. CategorySeeder (creates film categories)
3. StudioSeeder (creates cinema studios)
4. SeatSeeder (creates seats for each studio)
5. FilmSeeder (creates films with categories)
6. ShowtimeSeeder (creates showtimes)
   → Showtime model auto-creates showtime_seats
7. OrderSeeder (creates orders with tickets & payments)
```

---

## 📝 FILE SEEDER YANG DIBUAT

```
database/seeders/
├── DatabaseSeeder.php          # Main seeder
├── UserSeeder.php              # Admin & Customer users
├── CategorySeeder.php          # Film categories
├── StudioSeeder.php            # Cinema studios
├── SeatSeeder.php              # Seats per studio
├── FilmSeeder.php              # Films with categories
├── ShowtimeSeeder.php          # Showtime schedules
├── ShowtimeSeatSeeder.php      # Seat mapping (optional)
├── OrderSeeder.php             # Orders with tickets & payments
├── TicketSeeder.php            # (optional, handled by OrderSeeder)
└── PaymentSeeder.php           # (optional, handled by OrderSeeder)
```

---

## 🎯 FITUR YANG BISA DITEST

### ✅ **Working Features:**
1. User Registration & Login (Admin & Customer)
2. Film Browsing (Homepage with hero slider)
3. Film Detail View
4. Showtime Selection
5. Seat Selection with Real-time Lock
6. 5-Minute Countdown Timer
7. Checkout with Validation
8. Payment via Midtrans (Sandbox)
9. Order History with Status Tracking
10. Auto-cancel Expired Orders (Scheduled Command)
11. Auto-release Expired Locks (Scheduled Command)
12. Admin Dashboard
13. CRUD Films (with poster upload)
14. CRUD Showtimes (with conflict detection)
15. Order Management (view, cancel)
16. Profile Management (with avatar upload)

---

## 🚀 NEXT STEPS

### **Production Readiness:**
1. [ ] Setup Midtrans Production Keys
2. [ ] Configure SMTP for email notifications
3. [ ] Setup queue worker for background jobs
4. [ ] Enable HTTPS
5. [ ] Setup automated database backup
6. [ ] Configure monitoring & logging
7. [ ] Add rate limiting for booking endpoint

### **Enhancement Ideas:**
1. [ ] Email notification on successful booking
2. [ ] SMS notification for upcoming shows
3. [ ] QR code scanning at entrance
4. [ ] Seat availability heatmap
5. [ ] Film rating & reviews
6. [ ] Wishlist feature
7. [ ] Promo code system
8. [ ] Membership points system

---

## 📞 SUPPORT

Jika ada masalah dengan data dummy:
1. Cek log error di `storage/logs/laravel.log`
2. Pastikan database connection sudah benar
3. Jalankan ulang seeder dengan `php artisan migrate:refresh --seed`

---

**Last Updated:** 2026-02-27
**Database:** db-tiket (MySQL)
**Laravel Version:** 12.x
