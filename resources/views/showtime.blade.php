@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-10">
        <h1 class="text-3xl font-bold mb-2">
            🎟 Pilih Kursi
        </h1>
        <p class="text-gray-400">
            {{ $showtime->film->title }} •
            {{ \Carbon\Carbon::parse($showtime->show_date)->format('d M Y') }} •
            {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
        </p>
    </div>

    {{-- SCREEN --}}
    <div class="mb-12 text-center">
        <div class="bg-gradient-to-r from-gray-700 via-gray-500 to-gray-700 h-3 rounded-full mb-2"></div>
        <p class="text-gray-400 text-sm tracking-widest">L A Y A R</p>
    </div>

    {{-- INFO BOX --}}
    <div id="infoBox" class="hidden mb-6">
        <div class="bg-blue-500/20 border border-blue-500 text-blue-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <span class="text-2xl">ℹ️</span>
            <div>
                <p class="font-semibold">Kursi yang dipilih tersimpan sementara</p>
                <p class="text-sm">Jika halaman di-refresh, kursi masih tersimpan selama timer belum habis.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('checkout') }}" id="bookingForm">
        @csrf

        {{-- Notification Container --}}
        <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-3"></div>

        {{-- SEAT GRID --}}
        <div class="grid grid-cols-8 gap-4 justify-center mb-12">

            @foreach($showtime->showtimeSeats as $seat)

                @if($seat->status == 'available')
                    <label class="cursor-pointer">
                        <input type="checkbox"
                               name="showtime_seat_ids[]"
                               value="{{ $seat->id }}"
                               data-price="{{ $showtime->price }}"
                               data-seat-number="{{ $seat->seat->seat_number }}"
                               class="hidden peer seat-checkbox"
                               data-showtime-seat-id="{{ $seat->id }}">

                        <div class="py-3 rounded-lg text-sm font-semibold text-center
                                    bg-green-500 hover:bg-green-600
                                    peer-checked:bg-green-700
                                    transition duration-200">
                            {{ $seat->seat->seat_number }}
                        </div>
                    </label>

                @elseif($seat->status == 'locked')

                    <div class="py-3 rounded-lg text-sm font-semibold text-center bg-orange-500 opacity-70 cursor-not-allowed"
                         title="Locked by another user">
                        {{ $seat->seat->seat_number }}
                    </div>

                @else

                    <div class="py-3 rounded-lg text-sm font-semibold text-center bg-red-600 opacity-80 cursor-not-allowed"
                         title="Already booked">
                        {{ $seat->seat->seat_number }}
                    </div>

                @endif

            @endforeach

        </div>

        {{-- LEGEND --}}
        <div class="flex justify-center gap-8 mb-10 text-sm text-gray-400">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-500 rounded"></div>
                Available
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-orange-500 rounded"></div>
                Locked
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-600 rounded"></div>
                Booked
            </div>
        </div>

        {{-- COUNTDOWN TIMER --}}
        <div id="countdownContainer"
            class="hidden mb-8 text-center">

            <div class="inline-block bg-red-600/20 border border-red-500 text-red-400 px-6 py-3 rounded-lg text-sm font-semibold">
                ⏳ Waktu Tersisa:
                <span id="countdownTimer" class="ml-2 font-bold text-white">
                    05:00
                </span>
            </div>

            <div class="mt-3 text-sm text-gray-400">
                <p>⚠️ Jangan refresh halaman setelah memilih kursi</p>
                <p class="text-xs">Kursi akan otomatis dirilis jika waktu habis</p>
            </div>

        </div>

        {{-- SUMMARY --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 max-w-xl mx-auto">

            <div class="flex justify-between mb-4">
                <span>Kursi Dipilih:</span>
                <span id="selectedSeats" class="font-semibold text-white">-</span>
            </div>

            <div class="flex justify-between mb-6">
                <span>Total Harga:</span>
                <span id="totalPrice" class="font-bold text-red-500">
                    Rp 0
                </span>
            </div>

            <div class="mb-6 text-xs text-gray-400 text-center">
                <p>💡 Tips: Kursi yang dipilih akan tersimpan sementara</p>
                <p>Jika ter-refresh, kursi masih tersimpan selama timer belum habis</p>
            </div>

            <button type="submit"
                    id="checkoutButton"
                    class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-lg font-semibold transition opacity-50 cursor-not-allowed"
                    disabled>
                🎬 Checkout
            </button>

        </div>

    </form>

</div>


{{-- JAVASCRIPT TOTAL CALC --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.seat-checkbox');
    const totalPriceEl = document.getElementById('totalPrice');
    const selectedSeatsEl = document.getElementById('selectedSeats');
    const countdownContainer = document.getElementById('countdownContainer');
    const countdownTimer = document.getElementById('countdownTimer');
    const checkoutButton = document.getElementById('checkoutButton');
    const infoBox = document.getElementById('infoBox');
    const bookingForm = document.getElementById('bookingForm');

    let countdownInterval = null;
    let expireTime = null;
    let isRecovered = false;
    let notificationQueue = [];

    // LocalStorage keys
    const STORAGE_KEY_SELECTED_SEATS = 'booking_selected_seats_' + {{ $showtime->id }};
    const STORAGE_KEY_EXPIRE_TIME = 'booking_expire_time_' + {{ $showtime->id }};
    const STORAGE_KEY_LOCKED_SEATS = 'booking_locked_seats_' + {{ $showtime->id }};

    // Get current time in milliseconds
    const currentTime = new Date().getTime();

    /**
     * Show modern notification
     */
    function showNotification(options) {
        const {
            type = 'info', // success, error, warning, info
            title,
            message = '',
            duration = 5000,
            showClose = true,
            onClose
        } = options;

        const container = document.getElementById('notificationContainer');

        // Icons and colors based on type
        const config = {
            success: {
                icon: '✅',
                bgColor: 'bg-green-500/95',
                borderColor: 'border-green-400',
                textColor: 'text-white'
            },
            error: {
                icon: '❌',
                bgColor: 'bg-red-500/95',
                borderColor: 'border-red-400',
                textColor: 'text-white'
            },
            warning: {
                icon: '⚠️',
                bgColor: 'bg-yellow-500/95',
                borderColor: 'border-yellow-400',
                textColor: 'text-white'
            },
            info: {
                icon: 'ℹ️',
                bgColor: 'bg-blue-500/95',
                borderColor: 'border-blue-400',
                textColor: 'text-white'
            }
        };

        const { icon, bgColor, borderColor, textColor } = config[type] || config.info;

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `${bgColor} ${textColor} px-6 py-4 rounded-xl shadow-2xl border ${borderColor} backdrop-blur-sm transform transition-all duration-300 ease-out max-w-md`;
        notification.style.cssText = `
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        `;

        notification.innerHTML = `
            <div class="flex items-start gap-3">
                <span class="text-2xl flex-shrink-0">${icon}</span>
                <div class="flex-1">
                    ${title ? `<p class="font-semibold text-sm mb-1">${title}</p>` : ''}
                    ${message ? `<p class="text-sm opacity-90">${message}</p>` : ''}
                </div>
                ${showClose ? `
                    <button onclick="this.closest('.${bgColor}').remove()" 
                            class="text-white/70 hover:text-white transition text-lg leading-none">
                        ✕
                    </button>
                ` : ''}
            </div>
        `;

        // Add animation keyframes
        if (!document.getElementById('notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        container.appendChild(notification);

        // Auto-remove after duration
        if (duration > 0) {
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => {
                    notification.remove();
                    if (onClose) onClose();
                }, 300);
            }, duration);
        }

        return notification;
    }

    /**
     * Show confirm dialog (modern version)
     */
    function showConfirmDialog(options) {
        return new Promise((resolve) => {
            const {
                type = 'info',
                title,
                message = '',
                confirmText = 'OK',
                cancelText = 'Batal',
                confirmClass = 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600'
            } = options;

            const container = document.getElementById('notificationContainer');

            const config = {
                success: { icon: '✅', bgColor: 'bg-gradient-to-br from-green-500 to-green-600', borderColor: 'border-green-400' },
                error: { icon: '❌', bgColor: 'bg-gradient-to-br from-red-500 to-red-600', borderColor: 'border-red-400' },
                warning: { icon: '⚠️', bgColor: 'bg-gradient-to-br from-yellow-500 to-orange-500', borderColor: 'border-yellow-400' },
                info: { icon: 'ℹ️', bgColor: 'bg-gradient-to-br from-blue-500 to-indigo-600', borderColor: 'border-blue-400' },
                checkout: { icon: '🎬', bgColor: 'bg-gradient-to-br from-red-600 via-red-500 to-red-600', borderColor: 'border-red-400' }
            };

            const { icon, bgColor, borderColor } = config[type] || config.info;

            // Create backdrop with blur
            const backdrop = document.createElement('div');
            backdrop.className = 'fixed inset-0 bg-black/70 backdrop-blur-md z-50 flex items-center justify-center p-4';
            backdrop.style.cssText = `
                animation: fadeIn 0.3s ease-out;
                background: radial-gradient(circle at center, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.8) 100%);
            `;

            // Create modal with glassmorphism effect
            const modal = document.createElement('div');
            modal.className = `${bgColor} text-white px-0 py-0 rounded-3xl shadow-2xl border ${borderColor} max-w-lg w-full transform transition-all duration-300 scale-100 overflow-hidden`;
            modal.style.cssText = `
                box-shadow: 0 25px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.1);
                animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            `;

            // Parse message for line breaks and special formatting
            const formattedMessage = message ? message.replace(/\n/g, '<br>').replace(/(\*\*.*?\*\*)/g, '<strong>$1</strong>') : '';

            modal.innerHTML = `
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>
                    
                    <div class="px-8 pt-8 pb-6">
                        <div class="text-center mb-2">
                            <span class="text-7xl block mb-4 drop-shadow-lg transform hover:scale-110 transition duration-300">${icon}</span>
                            ${title ? `<h3 class="text-2xl md:text-3xl font-bold mb-3 drop-shadow-lg">${title}</h3>` : ''}
                            ${formattedMessage ? `<div class="text-sm md:text-base opacity-95 leading-relaxed">${formattedMessage}</div>` : ''}
                        </div>
                    </div>
                    
                    <div class="px-8 pb-8">
                        <div class="flex gap-3">
                            <button class="confirm-btn flex-1 ${confirmClass} text-white px-6 py-4 rounded-2xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                ${confirmText}
                            </button>
                            <button class="cancel-btn flex-1 bg-white/15 hover:bg-white/25 backdrop-blur-sm text-white px-6 py-4 rounded-2xl font-bold text-lg transition-all duration-300 transform hover:scale-105 border border-white/20">
                                ${cancelText}
                            </button>
                        </div>
                    </div>
                </div>
            `;

            // Add animations
            if (!document.getElementById('modal-styles')) {
                const style = document.createElement('style');
                style.id = 'modal-styles';
                style.textContent = `
                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                    @keyframes modalSlideUp {
                        from {
                            opacity: 0;
                            transform: translateY(50px) scale(0.9);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0) scale(1);
                        }
                    }
                    @keyframes modalSlideDown {
                        from {
                            opacity: 1;
                            transform: translateY(0) scale(1);
                        }
                        to {
                            opacity: 0;
                            transform: translateY(50px) scale(0.9);
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            backdrop.appendChild(modal);
            container.appendChild(backdrop);

            // Handle confirm
            modal.querySelector('.confirm-btn').addEventListener('click', () => {
                modal.style.animation = 'modalSlideDown 0.3s ease-out';
                setTimeout(() => {
                    backdrop.remove();
                    resolve(true);
                }, 300);
            });

            // Handle cancel
            modal.querySelector('.cancel-btn').addEventListener('click', () => {
                modal.style.animation = 'modalSlideDown 0.3s ease-out';
                setTimeout(() => {
                    backdrop.remove();
                    resolve(false);
                }, 300);
            });

            // Close on backdrop click
            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) {
                    modal.style.animation = 'modalSlideDown 0.3s ease-out';
                    setTimeout(() => {
                        backdrop.remove();
                        resolve(false);
                    }, 300);
                }
            });

            // ESC key to close
            const escHandler = (e) => {
                if (e.key === 'Escape') {
                    modal.style.animation = 'modalSlideDown 0.3s ease-out';
                    setTimeout(() => {
                        backdrop.remove();
                        document.removeEventListener('keydown', escHandler);
                        resolve(false);
                    }, 300);
                }
            };
            document.addEventListener('keydown', escHandler);
        });
    }

    /**
     * Save booking data to localStorage
     */
    function saveToLocalStorage(seatIds, expireTimestamp) {
        try {
            localStorage.setItem(STORAGE_KEY_SELECTED_SEATS, JSON.stringify(seatIds));
            localStorage.setItem(STORAGE_KEY_EXPIRE_TIME, expireTimestamp.toString());
            localStorage.setItem(STORAGE_KEY_LOCKED_SEATS, JSON.stringify(seatIds));
        } catch (e) {
            console.error('Error saving to localStorage:', e);
        }
    }

    /**
     * Clear booking data from localStorage
     */
    function clearLocalStorage() {
        try {
            localStorage.removeItem(STORAGE_KEY_SELECTED_SEATS);
            localStorage.removeItem(STORAGE_KEY_EXPIRE_TIME);
            localStorage.removeItem(STORAGE_KEY_LOCKED_SEATS);
        } catch (e) {
            console.error('Error clearing localStorage:', e);
        }
    }

    /**
     * Get booking data from localStorage
     */
    function getFromLocalStorage() {
        try {
            const selectedSeats = JSON.parse(localStorage.getItem(STORAGE_KEY_SELECTED_SEATS) || '[]');
            const expireTime = localStorage.getItem(STORAGE_KEY_EXPIRE_TIME);
            const lockedSeats = JSON.parse(localStorage.getItem(STORAGE_KEY_LOCKED_SEATS) || '[]');

            return {
                selectedSeats,
                expireTime: expireTime ? parseInt(expireTime) : null,
                lockedSeats
            };
        } catch (e) {
            console.error('Error reading from localStorage:', e);
            return { selectedSeats: [], expireTime: null, lockedSeats: [] };
        }
    }

    /**
     * Release seats from server
     */
    function releaseSeats(seatIds) {
        seatIds.forEach(seatId => {
            fetch("/lock-seat/" + seatId, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('Seat released:', seatId, data);
            })
            .catch(err => console.error('Error releasing seat:', err));
        });
    }

    /**
     * Start countdown timer
     */
    function startCountdown(expiresAt) {
        expireTime = new Date(expiresAt).getTime();
        countdownContainer.classList.remove('hidden');
        infoBox.classList.remove('hidden');

        // Update timer immediately
        updateTimerDisplay();

        // Clear any existing interval
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }

        // Then update every second
        countdownInterval = setInterval(updateTimerDisplay, 1000);
    }

    /**
     * Update timer display
     */
    function updateTimerDisplay() {
        if (!expireTime) return;

        const now = new Date().getTime();
        const distance = expireTime - now;

        if (distance <= 0) {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            countdownTimer.innerText = "00:00";
            checkoutButton.disabled = true;
            checkoutButton.classList.add('opacity-50', 'cursor-not-allowed');

            // Clear localStorage
            clearLocalStorage();

            // Show expired notification
            showNotification({
                type: 'error',
                title: '⏰ Waktu Pemesanan Habis!',
                message: 'Kursi akan dikembalikan. Silakan pilih kursi lagi.',
                duration: 3000,
                showClose: false,
                onClose: () => {
                    location.reload();
                }
            });

            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownTimer.innerText =
            String(minutes).padStart(2, '0') + ":" +
            String(seconds).padStart(2, '0');

        // Warn user when time is running out
        if (distance < 60000) { // Less than 1 minute
            countdownTimer.classList.add('text-red-600', 'animate-pulse');
        } else {
            countdownTimer.classList.remove('text-red-600', 'animate-pulse');
        }
    }

    /**
     * Update total price and selected seats display
     */
    function updateTotal() {
        let total = 0;
        let seats = [];
        let selectedSeatIds = [];

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseFloat(cb.dataset.price);
                seats.push(cb.dataset.seatNumber);
                selectedSeatIds.push(cb.dataset.showtimeSeatId);
            }
        });

        totalPriceEl.innerText = "Rp " + total.toLocaleString('id-ID');
        selectedSeatsEl.innerText = seats.length ? seats.join(', ') : '-';

        // Save to localStorage if there are selected seats and timer is running
        if (selectedSeatIds.length > 0 && expireTime) {
            saveToLocalStorage(selectedSeatIds, expireTime);
        }

        // Enable/disable checkout button
        if (seats.length === 0) {
            checkoutButton.disabled = true;
            checkoutButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            checkoutButton.disabled = false;
            checkoutButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    /**
     * Attach event listeners to checkboxes
     */
    function attachCheckboxListeners() {
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (this.checked) {
                    // Lock the seat
                    fetch("/lock-seat/" + this.dataset.showtimeSeatId, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.message !== "Seat locked successfully") {
                            showNotification({
                                type: 'error',
                                title: 'Gagal Lock Kursi',
                                message: data.message
                            });
                            this.checked = false;
                            updateTotal();
                        } else {
                            updateTotal();

                            // Start or restart timer
                            if (!countdownInterval || !expireTime) {
                                startCountdown(data.expires_at);
                            } else {
                                // Extend timer if already running
                                expireTime = new Date(data.expires_at).getTime();
                            }
                        }
                    })
                    .catch(() => {
                        showNotification({
                            type: 'error',
                            title: 'Error',
                            message: 'Gagal mengunci kursi. Silakan coba lagi.'
                        });
                        this.checked = false;
                        updateTotal();
                    });
                } else {
                    updateTotal();
                }
            });
        });
    }

    /**
     * Recover selected seats from localStorage
     */
    function recoverSelectedSeats() {
        const { selectedSeats, expireTime: storedExpireTime } = getFromLocalStorage();

        if (selectedSeats.length > 0 && storedExpireTime) {
            // Check if expire time is still valid
            if (storedExpireTime > currentTime) {
                // Restore checkboxes
                let restoredCount = 0;
                selectedSeats.forEach(seatId => {
                    const checkbox = document.querySelector(`.seat-checkbox[data-showtime-seat-id="${seatId}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        restoredCount++;
                    }
                });

                if (restoredCount > 0) {
                    // Start countdown
                    startCountdown(new Date(storedExpireTime));

                    // Update total
                    updateTotal();

                    // Show info message
                    showNotification({
                        type: 'success',
                        title: 'Kursi Berhasil Dipulihkan!',
                        message: `${restoredCount} kursi terpilih. Silakan lanjutkan pembayaran.`,
                        duration: 5000
                    });

                    isRecovered = true;
                    return true;
                }
            } else {
                // Expired, clear localStorage
                clearLocalStorage();
            }
        }

        return false;
    }

    /**
     * Handle form submission
     */
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default submission

        const { selectedSeats, expireTime: storedExpireTime } = getFromLocalStorage();

        if (selectedSeats.length === 0) {
            showNotification({
                type: 'warning',
                title: 'Pilih Kursi Dulu!',
                message: 'Silakan pilih kursi yang ingin dipesan.'
            });
            return false;
        }

        console.log('🔍 Checking seats status...', {
            selectedSeats,
            storedExpireTime,
            hasExpireTime: !!storedExpireTime
        });

        // Validate seats status with server before checkout
        // This is the SOURCE OF TRUTH - server validation overrides local timer
        fetch("{{ route('seats.check') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                showtime_seat_ids: selectedSeats
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log('📊 Server response:', data);

            // Check if seats are valid
            if (!data.valid) {
                // Some seats are invalid
                let message = 'Beberapa kursi tidak tersedia:\n\n';
                data.invalid_seats.forEach(seat => {
                    message += `- ${seat.seat_number}: ${seat.reason}\n`;
                });
                
                console.error('❌ Invalid seats:', data.invalid_seats);
                
                showNotification({
                    type: 'error',
                    title: 'Kursi Tidak Tersedia',
                    message: message,
                    duration: 6000,
                    onClose: () => {
                        clearLocalStorage();
                        location.reload();
                    }
                });
                return;
            }

            // Seats are valid! Check time remaining
            const timeRemaining = data.time_remaining;
            
            console.log('✅ Seats valid, time remaining:', timeRemaining + 's');

            // IMPORTANT: If time_remaining is very low (< 10 seconds) but seats are valid,
            // it means user just locked the seats. Extend the lock to give user full 5 minutes.
            if (timeRemaining < 10) {
                console.log('⏱️ Time < 10s, extending lock...');
                // User just locked seats, extend to get full 5 minutes
                extendLockAndProceed(selectedSeats);
                return;
            }
            
            // If server says time >= 30 seconds, proceed to checkout
            if (timeRemaining >= 30) {
                console.log('✅ Time >= 30s, proceeding to checkout');
                // Plenty of time, proceed directly to checkout
                showConfirmDialog({
                    type: 'checkout',
                    title: 'Lanjutkan Pembayaran?',
                    message: `Kursi: ${selectedSeats.length} kursi\n\n⚠️ Jangan refresh halaman saat proses checkout!`,
                    confirmText: '🎬 Bayar Sekarang',
                    cancelText: 'Batal',
                    confirmClass: 'bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600'
                })
                .then(confirmed => {
                    if (confirmed) {
                        // Clear localStorage before submit
                        clearLocalStorage();
                        // Submit form
                        bookingForm.submit();
                    }
                });
                return;
            }

            // Time is low (10-29 seconds), offer to extend
            if (timeRemaining >= 10 && timeRemaining < 30) {
                console.log('⏱️ Time 10-29s, offering extend');
                showConfirmDialog({
                    type: 'warning',
                    title: '⏰ Waktu Hampir Habis!',
                    message: `Waktu tersisa: ${timeRemaining} detik\n\nKlik "Perpanjang" untuk reset waktu ke 5 menit.`,
                    confirmText: '🔄 Perpanjang Waktu',
                    cancelText: 'Batal',
                    confirmClass: 'bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white'
                })
                .then(confirmed => {
                    if (confirmed) {
                        extendLockAndProceed(selectedSeats);
                    }
                });
                return;
            }

            // Fallback: should not reach here if seats are valid
            console.warn('⚠️ Unexpected state, proceeding with checkout anyway');
            showConfirmDialog({
                type: 'info',
                title: 'Lanjutkan Pembayaran?',
                message: `Kursi: ${selectedSeats.length} kursi`,
                confirmText: 'Bayar Sekarang',
                cancelText: 'Batal',
                confirmClass: 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600'
            })
            .then(confirmed => {
                if (confirmed) {
                    clearLocalStorage();
                    bookingForm.submit();
                }
            });
        })
        .catch(error => {
            console.error('❌ Error checking seats:', error);
            showNotification({
                type: 'error',
                title: 'Error',
                message: 'Gagal memvalidasi kursi. Silakan coba lagi.'
            });
        });
    });

    /**
     * Extend lock for selected seats and proceed to checkout
     */
    function extendLockAndProceed(seatIds) {
        console.log('🔒 Extending lock for', seatIds.length, 'seats...');
        
        showNotification({
            type: 'info',
            title: 'Memproses...',
            message: 'Mengunci kursi dengan timer 5 menit...',
            duration: 0,
            showClose: false
        });

        let extendedCount = 0;
        const extendPromises = seatIds.map(seatId => {
            return fetch("/lock-seat/" + seatId, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('🔓 Lock response for seat:', data);
                if (data.message === 'Seat locked successfully' || data.message === 'Seat lock extended') {
                    extendedCount++;
                    // Update to the latest expires_at
                    if (data.expires_at) {
                        expireTime = new Date(data.expires_at).getTime();
                        startCountdown(data.expires_at);
                    }
                }
                return data;
            })
            .catch(err => {
                console.error('❌ Error extending lock:', err);
                return null;
            });
        });

        // Wait for all extensions to complete
        Promise.all(extendPromises).then(() => {
            // Remove loading notification
            const container = document.getElementById('notificationContainer');
            container.innerHTML = '';
            
            if (extendedCount === seatIds.length) {
                console.log('✅ All seats extended successfully');
                // Successfully extended, now proceed to checkout
                showConfirmDialog({
                    type: 'success',
                    title: '✅ Kursi Terkunci!',
                    message: `🎫 <strong>Waktu: 5 menit</strong>\n\n🪑 Kursi: ${seatIds.length} kursi\n\n✨ Silakan lanjutkan pembayaran.`,
                    confirmText: '🎬 Bayar Sekarang',
                    cancelText: 'Batal',
                    confirmClass: 'bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600'
                })
                .then(confirmed => {
                    if (confirmed) {
                        clearLocalStorage();
                        bookingForm.submit();
                    }
                });
            } else {
                console.error('❌ Only', extendedCount, 'of', seatIds.length, 'seats extended');
                showNotification({
                    type: 'error',
                    title: 'Gagal Lock Kursi',
                    message: 'Beberapa kursi gagal dikunci. Silakan coba lagi.'
                });
            }
        });
    }

    // Initialize
    const recovered = recoverSelectedSeats();

    // Attach event listeners (always, whether recovered or not)
    attachCheckboxListeners();

    // If NOT recovered (fresh session), clear any old localStorage data
    // This prevents "timeout" error on first-time checkout
    if (!recovered) {
        clearLocalStorage();
    }

    // Warn before leaving page if booking in progress
    window.addEventListener('beforeunload', function(e) {
        const { selectedSeats, expireTime: storedExpireTime } = getFromLocalStorage();

        if (selectedSeats.length > 0 && storedExpireTime && storedExpireTime > currentTime) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });

});
</script>
@endsection
